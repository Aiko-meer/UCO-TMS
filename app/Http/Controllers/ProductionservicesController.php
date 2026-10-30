<?php

namespace App\Http\Controllers;
use App\Models\Departments;
use App\Models\User;
use App\Models\Publishing_requests;
use App\Models\Publishing_request_informaitons;
use App\Models\Production_request_information;
use App\Models\Socmed_request;
use App\Models\Soft_copy_requests;
use App\Models\TrademarkRequest;
use App\Models\Photo_vid_requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProductionservicesController extends Controller
{
    //
     public function dashboard()
    {
        $socmed = Socmed_request::where('token', '0')->get();
        $photovid = Photo_vid_requests::where('token', '0')->get();
        $softcopy = Soft_copy_requests::where('token', '0')->get();
        $trade = TrademarkRequest::where('token', '0')->get();
        $layout = Publishing_requests::where('token', '0')
        ->whereHas('information', function ($query) {
            $query->where('specification', 'layout');
        })
        ->get();
         $production = Publishing_requests::where('token', '0')
        ->whereHas('information', function ($query) {
            $query->where('specification', 'production');
        })
        ->get();
         $uzpr = Publishing_requests::where('token', '0')
        ->whereHas('information', function ($query) {
            $query->where('specification', 'uzpr');
        })
        ->get();
         $uzpost = Publishing_requests::where('token', '0')
        ->whereHas('information', function ($query) {
            $query->where('specification', 'uzpost');
        })
        ->get();

        $totalNotifications = $socmed->count() + $photovid->count() + $layout->count() + $production->count() + $uzpr->count() + $uzpost->count() + $softcopy->count() + $trade->count();
         $departments = Departments::all();
         $requests = Publishing_requests::with(['information', 'production'])
         ->whereHas('information', function ($query) {$query->where('specification', 'production');})->orderBy('id', 'desc')->get();
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

        $actviepagi  = Publishing_requests::with('information')->paginate(5, ['fullname'], 'active_page');
        $archivepagi =Publishing_requests::with('information')->paginate(10);
        $monthpagi =Publishing_requests::with('information')->paginate(10);
        $listpagi =Publishing_requests::with('information')->paginate(5);
        
          // Inside your controller method:
        $year = request('year', date('Y')); // Defaults to current year if not selected
        $month = request('month', date('m')); // Defaults to current month if not selected
         $archived = Publishing_requests::with('information')
            ->whereHas('information', function ($query) use ($year, $month) {
                $query->whereIn('status', [2, 4])
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month);
            })
            ->get();

             $totalRequests = Publishing_requests::whereHas('information', function($q) {
                                    $q->where('specification', 'production');
                                })->count();
            // 2. Get counts for each status
        // If 'status' is inside the related 'information' table:
            $inProgressCount = Publishing_requests::whereHas('information', function($q) {
                    $q->whereIn('status', [2, 4])
                    ->where('specification', 'production');
                })->count();

            $pendingCount = Publishing_requests::whereHas('information', function($q) {
                    $q->where('status', 0)
                    ->where('specification', 'production');
                })->count();

            $approvalCount = Publishing_requests::whereHas('information', function($q) {
                    $q->where('status', 1)
                    ->where('specification', 'production');
                })->count();

            $monthCount = Publishing_requests::whereHas('information', function($q) {
                $q->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('specification', 'production');
            })->count();

            $totalCount =  Publishing_requests::whereHas('information', function($q) {
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
            'actviepagi',
            'archivepagi',
            'monthpagi',
            'listpagi',
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
            'full_name' => 'required|string',
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
            } while (Publishing_requests::where('request_id', $randomRequestId)->exists());

            // 3. Save to the parent table (socmed_requests)
            $publishingRequest = Publishing_requests::create([
                'request_id' => $randomRequestId,
                'email' => $validatedData['email'],
                'fullname' => $validatedData['full_name'],
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
            ]);

             // 4. Save to the child table (socmed_request_information)
            Production_request_information::create([
                'request_id' => $randomRequestId, // Links both tables together
                'size' => $validatedData['size'],
                'quantity' => $validatedData['quantity'],
                'type_material' => $validatedData['type_material']
            ]);

        });
         try {
   DB::commit(); // Finalize all inserts if everything succeeds
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

        return redirect()->back()->with('success', 'Request updated successfully!');
   }
}

