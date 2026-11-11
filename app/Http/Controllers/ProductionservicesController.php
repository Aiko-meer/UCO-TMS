<?php

namespace App\Http\Controllers;
use App\Models\Departments;
use App\Models\User;
use App\Models\Requestor;
use App\Models\Publishing_request_informaitons;
use App\Models\Soft_copy_requests;
use App\Models\TrademarkRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Events\SocmedRequestUpdated;
use App\Support\SafeBroadcast;

class ProductionservicesController extends Controller
{
    //
     public function dashboard()
    {

         $departments = Departments::all();
         $requests = Requestor::with('publishing')
         ->whereHas('publishing', function ($query) {$query->where('specification', 'production');})->orderBy('id', 'desc')->get();
         $users = User::whereIn('user_type', ['creative_team', 'creative_admin'])
    ->withCount([
        'layoutRequests as in_progress_count' => function ($query) {
            $query->where('status', 2)
                  ->where('specification', 'production'); // Change 'specification' to your actual column name if it differs
        },
        'layoutRequests as in_pending_count' => function ($query) {
            $query->where('status', 0)
                  ->where('specification', 'production'); // Change 'specification' to your actual column name if it differs
        },
        'layoutRequests as approved_count' => function ($query) {
            $query->where('status', 1)
                  ->where('specification', 'production'); // Change 'specification' to your actual column name if it differs
        },
        'layoutRequests as total_count' => function ($query) {
            $query->where('specification', 'production'); // Change 'specification' to your actual column name if it differs
        }
    ])
    ->get();

        
         // Inside your controller method:
        // Month/year are optional: the archive table filters client-side, so load
        // every finished request and let its own selectors narrow the list.
        $year = request('year');
        $month = request('month');
         $archived = Requestor::with('publishing')
            ->whereHas('publishing', function ($query) use ($year, $month) {
                $query->whereIn('status', [1, 4])
                    ->where('specification', 'production')
                    ->when($year, fn ($q) => $q->whereYear('created_at', $year))
                    ->when($month, fn ($q) => $q->whereMonth('created_at', $month));
            })
            ->get();

             $totalRequests = Requestor::whereHas('publishing', function($q) {
                                    $q->where('specification', 'production');
                                })->count();
            // 2. Get counts for each status
        // If 'status' is inside the related 'information' table:
            $inProgressCount = Requestor::whereHas('publishing', function($q) {
                    $q->whereIn('status', [2, 4])
                    ->where('specification', 'production');
                })->count();

            $pendingCount = Requestor::whereHas('publishing', function($q) {
                    $q->where('status', 0)
                    ->where('specification', 'production');
                })->count();

            $approvalCount = Requestor::whereHas('publishing', function($q) {
                    $q->where('status', 1)
                    ->where('specification', 'production');
                })->count();

            $monthCount = Requestor::whereHas('publishing', function($q) {
                $q->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('specification', 'production');
            })->count();

            $totalCount =  Requestor::whereHas('publishing', function($q) {
                                    $q->where('specification', 'production');
                                })->count();

             // 3. Calculate percentages safely (avoiding division by zero)
    $inProgressPercentage = $totalRequests > 0 ? round(($inProgressCount / $totalRequests) * 100) : 0;
    $pendingPercentage = $totalRequests > 0 ? round(($pendingCount / $totalRequests) * 100) : 0;
    $approvalPercentage = $totalRequests > 0 ? round(($approvalCount / $totalRequests) * 100) : 0;
        return view('admin.creatives.production.index', compact(
            'departments',
            'requests',
            'users',
            'archived',
            'inProgressCount',
            'pendingCount',
            'approvalCount',
            'inProgressPercentage',
            'pendingPercentage',
            'approvalPercentage',
            'monthCount',
            'totalCount',
            ));
    }

    public function store(Request $request)
    {
        // 1. Validate all form inputs matching your models
        $validatedData = $request->validate([
            'f_name' => 'required|string',
            'l_name' => 'required|string',
            'middle' => 'nullable|string',
            'department' => 'required|string',
            'email' => 'required|email',
            'date_needed' => 'required|string',
            'section' => 'required|string',
            'category' => 'required|string',
            'other_category' => 'nullable|string',
            'purpose' => 'required|string',
            'content_info' => 'nullable|string',
            'content_attachment' => 'url|nullable',
            'approve' => 'required|string',
            'category_sod' => 'nullable|string',
            'date_needed_socmed' => 'nullable|string',
            'size' => 'required|string',
            'quantity' => 'nullable|integer|min:1',
            'type_material' => 'nullable|string'

        ]);

        // The reference number is generated outside the transaction so the
        // exact value that is written to the database can be returned to the
        // requestor in the response.
        do {
            $requestId = Str::random(6);
        } while (Requestor::where('request_id', $requestId)->exists());

        try {
            DB::transaction(function () use ($request, $validatedData, $requestId) {

            // 3. Save to the parent table (socmed_requests)
            $publishingRequest = Requestor::create([
                'request_id' => $requestId,
                'email' => $validatedData['email'],
                'f_name' => $validatedData['f_name'],
                'l_name' => $validatedData['l_name'],
                'middle' => $validatedData['middle'],
                'department' => $validatedData['department'],
            ]);

            // 4. Save to the child table (socmed_request_information)
            Publishing_request_informaitons::create([
                'request_id' => $requestId, // Links both tables together
                'purpose' => $validatedData['purpose'],
                'date_needed' => $validatedData['date_needed'],
                'category' => $validatedData['category'],
                'other_category' => $validatedData['other_category'] ?? null,
                'specification' => 'production',
                'content_information' => $validatedData['content_info'] ?? null,
                'section' => $validatedData['section'],
                'approve' => $validatedData['approve'],
                'status' => '0', // Default status if applicable
                'reference' => $validatedData['content_attachment']?? null,
                'size' => $validatedData['size'],
                'quantity' => $validatedData['quantity'] ?? null,
                'type_material' => $validatedData['type_material'] ?? null
            ]);

            });

            // DB::transaction() already committed; dispatching after it keeps
            // the realtime refresh out of the success path.
            SafeBroadcast::dispatch(SocmedRequestUpdated::class);

            if ($request->wantsJson()) {
                return response()->json([
                    'ref' => $requestId,
                    'message' => 'Request saved successfully!',
                ], 201);
            }

            return redirect()->back()->with('success', 'Request saved successfully!');
        } catch (\Throwable $e) {
            report($e);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Failed to save your request. Please try again.',
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to save your request. Please try again.');
        }
     }

      public function update(Request $request, $request_id) {

       // Wrap in a transaction to ensure both updates succeed together
           DB::transaction(function () use ($request_id, $request) {
            // This will fail with a 404 if the main request doesn't exist (which is usually desired)
            $req = Publishing_request_informaitons::where('request_id', $request_id)->firstOrFail();

            // Updates the social media record if it exists; safely does nothing if it doesn't
            Publishing_request_informaitons::where('request_id', $request_id)->update([
                'produce' => $request->input('produce')
            ]);

            // Update main request fields
            $req->update([
                'status' => $request->input('status'),
                'produce' => $request->input('produce'),
            ]);

            // If status selected is 1, include the approval date
                if ($request->input('status') == 1) {
                    $updateData['date_approved'] = now(); // or date('Y-m-d H:i:s')
                }else{
                    $updateData['date_approved'] = null;
                }
        });
                SafeBroadcast::dispatch(SocmedRequestUpdated::class);
        return redirect()->back()->with('success', 'Request updated successfully!');
   }

   public function taketask(Request $request, $request_id)
   {
       $employeeId = auth()->user()->employee_id;
       abort_if(empty($employeeId), 403, 'Your account has no employee ID.');

       $req = Publishing_request_informaitons::where('request_id', $request_id)->firstOrFail();

       $req->update([
           'status'  => 2, // In Progress
           'produce' => $employeeId,
       ]);

       SafeBroadcast::dispatch(SocmedRequestUpdated::class);

       return redirect()->back()->with('success', 'You took this task!');
   }

   public function assignTask(Request $request, $request_id)
   {
       $data = $request->validate([
           'user_assigned' => ['required', 'string'],
       ]);

       $req = Publishing_request_informaitons::where('request_id', $request_id)->firstOrFail();

       $req->update([
           'status'  => 2, // In Progress
           'produce' => $data['user_assigned'],
       ]);

       SafeBroadcast::dispatch(SocmedRequestUpdated::class);

       return redirect()->back()->with('success', 'Request assigned successfully!');
   }

   public function destroy($requestId)
    {
        try {
            DB::transaction(function () use ($requestId) {
                // Delete the child first, then the parent
                Publishing_request_informaitons::where('request_id', $requestId)->delete();
                Requestor::where('request_id', $requestId)->delete();
            });

            SafeBroadcast::dispatch(SocmedRequestUpdated::class); // refresh live tables

            return redirect()->back()->with('success', 'Request deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete: ' . $e->getMessage());
        }
    }
}

