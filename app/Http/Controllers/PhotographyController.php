<?php

namespace App\Http\Controllers;
use App\Models\Departments;
use App\Models\Photo_vid_requests;
use App\Models\Photo_vid_request_informations;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Soft_copy_requests;
use App\Models\TrademarkRequest;
use App\Models\Publishing_requests;
use App\Models\Socmed_request;
use App\Models\Requestor;
use App\Events\SocmedRequestUpdated;
use Illuminate\Support\Facades\DB;

\App\Events\NotificationsUpdated::dispatch();
class PhotographyController extends Controller
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
      $users = User::query() // or simply User::
    ->withCount([
        'photoRequests as in_progress_count' => function ($query) {
            $query->where('status', 1);
        },
        'photoRequests as documented_count' => function ($query) {
            $query->where('status', 2);
        }
    ])
    ->get();
        // Fetches parent records along with their corresponding child information
       $requests = Requestor::with('photo')
    ->whereHas('photo', function ($query) {
        $query->whereIn('category', ['photo', 'audio', 'soft copy']);
    })
    ->orderBy('id', 'desc')
    ->get();
        $departments = Departments::all();
         $actviepagi  = Requestor::with('photo')->paginate(5, ['*'], 'active_page');
    $archivepagi =Requestor::with('photo')->paginate(10);
    $monthpagi =Requestor::with('photo')->paginate(10);
    $listpagi =Requestor::with('photo')->paginate(5);
    $totalRequests = Requestor::count();
    // 2. Get counts for each status
    // If 'status' is inside the related 'information' table:
        $inProgressCount = Requestor::whereHas('photo', function($q) {
            $q->where('status', 1); // Change to your actual in-progress status code
        })->count();

        $postedCount = Requestor::whereHas('photo', function($q) {
            $q->where('status', 2); // Posted status code
        })->count();

        $approvalCount = Requestor::whereHas('photo', function($q) {
            $q->where('status', 0); // Change to your actual approval status code (must be unique!)
        })->count();

        $monthCount = Requestor::whereHas('photo', function($q) {
            $q->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
        })->count();

        $totalCount = Requestor::whereHas('photo')->count();

         // 3. Calculate percentages safely (avoiding division by zero)
    $inProgressPercentage = $totalRequests > 0 ? round(($inProgressCount / $totalRequests) * 100) : 0;
    $postedPercentage = $totalRequests > 0 ? round(($postedCount / $totalRequests) * 100) : 0;
    $approvalPercentage = $totalRequests > 0 ? round(($approvalCount / $totalRequests) * 100) : 0;
         $requestors = Requestor::orderBy('l_name')->get();
     $month = request('month', date('m')); // Defaults to current month if not selected
     $year = request('year', date('Y')); // Defaults to current year if not selected
        $archived = Photo_vid_requests::with('information')
        ->whereHas('information', function ($query) use ($year, $month) {
            $query->where('status', 2)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month);
        })
        ->get();
        return view('admin.photography.index', compact(
            'departments',
            'requests',
            'archived',
            'actviepagi',
            'archivepagi',
            'monthpagi',
            'listpagi',
            'postedCount',
            'approvalCount',
            'monthCount',
            'totalCount',
            'inProgressPercentage',
            'postedPercentage',
            'approvalPercentage',
            'inProgressCount',
            'users',
           'socmed',
            'photovid',
            'layout',
            'production',
            'uzpost',
            'uzpr',
            'totalNotifications',
            'softcopy',
            'trade',
            'requestors'
        ));
    }

    public function store(Request $request){

         // 1. Validate all form inputs matching your models
        $validatedData = $request->validate([
            'email' => 'required|email',
            'f_name' => 'required|string',
            'l_name' => 'required|string',
            'middle' => 'nullable|string',
            'department' => 'required|string',
            'event' => 'required|string',
            'venue' => 'required|string',
            'start' => 'required|string',
            'end' => 'required|string',
            'purpose_doc' => 'required|string',
            'date_needed' => 'required|string',
            'category' => 'required|string',
            'section' => 'nullable|string',
            'approve' => 'nullable|string',

        ]);

         DB::transaction(function () use ($request, $validatedData) {

         // Generate a unique 6-character random request ID
            do {
                $randomRequestId = Str::random(6);
            } while (Requestor::where('request_id', $randomRequestId)->exists());

         // 3. Save to the parent table (socmed_requests)
            $photoRequest = Requestor::create([
                'request_id' => $randomRequestId,
                'email' => $validatedData['email'],
                'f_name' => $validatedData['f_name'],
                'l_name' => $validatedData['l_name'],
                'middle' => $validatedData['middle'],
                'department' => $validatedData['department'],
                'token' => '0',
            ]);

            // 4. Save to the child table (socmed_request_information)
            Photo_vid_request_informations::create([
                'request_id' => $randomRequestId, // Links both tables together
                'date_needed' => $validatedData['date_needed'],
                'category' => $validatedData['category'],
                'section' => $validatedData['section'] ?? null,
                'event' => $validatedData['event'],
                'venue' => $validatedData['venue'],
                'start_time' => $validatedData['start'],
                'end_time' => $validatedData['end'],
                'purpose_document' => $validatedData['purpose_doc'],
                'approve' => $validatedData['approve'],
                'status' => '0', // Default status if applicable
                 'request_id',
       
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

        // Find the main request record
       $req = Photo_vid_request_informations::where('request_id', $request_id)->firstOrFail();

        // Update main request fields
        $req->update([
            'status' => $request->input('status'),
            'produce' => $request->input('produce'),
        ]);
        SocmedRequestUpdated::dispatch();
   
        return redirect()->back()->with('success', 'Request updated successfully!');
   }

   public function destroy($request_id)
    {
        try {
            DB::transaction(function () use ($request_id) {
                // 1. Find the parent record or fail with a 404
                $photoRequest = Photo_vid_requests::where('request_id', $request_id)->firstOrFail();

                // 2. Delete the related child record(s) first
                Photo_vid_request_informations::where('request_id', $request_id)->delete();

                // 3. Delete the parent record
                $photoRequest->delete();
            });
            SocmedRequestUpdated::dispatch();
   
            return redirect()->back()->with('success', 'Request deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete: ' . $e->getMessage());
        }
    }

   public function markAsRead($id)
    {
        $socmed = Requestor::findOrFail($id);
        $socmed->token = '1'; // or 1 depending on your column type
        $socmed->save();

        return response()->json(['success' => true]);
    }

 public function markAsDenied(Request $request, $id)
        {
            $softCopy = Soft_copy_requests::findOrFail($id);
            $softCopy->status = 2; // 2 = Rejected / Denied
            $softCopy->save();

            return response()->json(['success' => true]);
        }    
}
