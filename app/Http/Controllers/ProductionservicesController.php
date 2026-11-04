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

class ProductionservicesController extends Controller
{
    //
     public function dashboard()
    {
        $socmed = Requestor::where('token', '0')->has('socmed')->get();
        $photovid = Requestor::where('token', '0')->has('photo')->get();
        $softcopy = Soft_copy_requests::where('token', '0')->get();
        $trade = TrademarkRequest::where('token', '0')->get();
        $layout = Requestor::where('token', '0')
        ->whereHas('publishing', function ($query) {
            $query->where('specification', 'layout');
        })
        ->get();
         $production = Requestor::where('token', '0')
        ->whereHas('publishing', function ($query) {
            $query->where('specification', 'production');
        })
        ->get();
         $uzpr = Requestor::where('token', '0')
        ->whereHas('publishing', function ($query) {
            $query->where('specification', 'uzpr');
        })
        ->get();
         $uzpost = Requestor::where('token', '0')
        ->whereHas('publishing', function ($query) {
            $query->where('specification', 'uzpost');
        })
        ->get();

        $totalNotifications = $socmed->count() + $photovid->count() + $layout->count() + $production->count() + $uzpr->count() + $uzpost->count() + $softcopy->count() + $trade->count();
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
        $year = request('year', date('Y')); // Defaults to current year if not selected
        $month = request('month', date('m')); // Defaults to current month if not selected
         $archived = Requestor::with('publishing')
            ->whereHas('publishing', function ($query) use ($year, $month) {
                $query->whereIn('status', [2, 4])
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month);
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
            'socmed',
            'photovid',
            'layout',
            'production',
            'uzpost',
            'uzpr',
            'totalNotifications',
            'softcopy',
            'trade'
        ));
    }

    public function store(Request $request)
    {
        // 1. Validate all form inputs matching your models
        $validatedData = $request->validate([
            'f_name' => 'required|string',
            'l_name' => 'required|string',
            'middle' => 'required|string',
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
            'category_sod' => 'string',
            'date_needed_socmed' => 'nullable|string',
            'size' => 'required|string',
            'quantity' => 'nullable|string',
            'type_material' => 'nullable|string'

        ]);

        DB::transaction(function () use ($request, $validatedData) {

            // Generate a unique 6-character random request ID
            do {
                $randomRequestId = Str::random(6);
            } while (Requestor::where('request_id', $randomRequestId)->exists());

            // 3. Save to the parent table (socmed_requests)
            $publishingRequest = Requestor::create([
                'request_id' => $randomRequestId,
                'email' => $validatedData['email'],
                'f_name' => $validatedData['f_name'],
                'l_name' => $validatedData['l_name'],
                'middle' => $validatedData['middle'],
                'department' => $validatedData['department'],
            ]);

            // 4. Save to the child table (socmed_request_information)
            Publishing_request_informaitons::create([
                'request_id' => $randomRequestId, // Links both tables together
                'purpose' => $validatedData['purpose'],
                'date_needed' => $validatedData['date_needed'],
                'category' => $validatedData['category'],
                'other_category' =>$validatedData['other_category'],
                'specification' => 'production',
                'content_information' => $validatedData['content_info'] ?? null,
                'section' => $validatedData['section'],
                'approve' => $validatedData['approve'],
                'status' => '0', // Default status if applicable
                'reference' => $validatedData['content_attachment']?? null,
                'size' => $validatedData['size'],
                'quantity' => $validatedData['quantity'],
                'type_material' => $validatedData['type_material']
            ]);

        });
         try {
   DB::commit(); // Finalize all inserts if everything succeeds
    SocmedRequestUpdated::dispatch();
    return redirect()->back()->with('success', 'Request saved successfully!');

} catch (\Exception $e) {
    DB::rollBack(); // Undo any partial database inserts if an error occurs
    return redirect()->back()->with('error', 'Failed to save: ' . $e->getMessage());
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
                SocmedRequestUpdated::dispatch();
        return redirect()->back()->with('success', 'Request updated successfully!');
   }

   public function destroy($requestId)
    {
        try {
            DB::transaction(function () use ($requestId) {
                // Delete the child first, then the parent
                Publishing_request_informaitons::where('request_id', $requestId)->delete();
                Requestor::where('request_id', $requestId)->delete();
            });

            SocmedRequestUpdated::dispatch(); // refresh live tables

            return redirect()->back()->with('success', 'Request deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete: ' . $e->getMessage());
        }
    }
}

