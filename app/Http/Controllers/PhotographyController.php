<?php

namespace App\Http\Controllers;
use App\Models\Departments;
use App\Models\Photo_vid_requests;
use App\Models\Photo_vid_request_informations;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PhotographyController extends Controller
{
    //
    public function dashboard()
    {
        $users = User::where('user_type', 'content_team')->get();
        // Fetches parent records along with their corresponding child information
       $requests = Photo_vid_requests::with('information')
    ->whereHas('information', function ($query) {
        $query->whereIn('category', ['photo', 'audio', 'soft copy']);
    })
    ->orderBy('id', 'desc')
    ->get();
        $departments = Departments::all();
         $actviepagi  = Photo_vid_requests::with('information')->paginate(5, ['fullname'], 'active_page');
    $archivepagi =Photo_vid_requests::with('information')->paginate(10);
    $monthpagi =Photo_vid_requests::with('information')->paginate(10);
    $listpagi =Photo_vid_requests::with('information')->paginate(5);
    $totalRequests = Photo_vid_requests::count();
    // 2. Get counts for each status
    // If 'status' is inside the related 'information' table:
        $inProgressCount = Photo_vid_requests::whereHas('information', function($q) {
            $q->where('status', 1); // Change to your actual in-progress status code
        })->count();

        $postedCount = Photo_vid_requests::whereHas('information', function($q) {
            $q->where('status', 2); // Posted status code
        })->count();

        $approvalCount = Photo_vid_requests::whereHas('information', function($q) {
            $q->where('status', 0); // Change to your actual approval status code (must be unique!)
        })->count();

        $monthCount = Photo_vid_requests::whereHas('information', function($q) {
            $q->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
        })->count();

        $totalCount = Photo_vid_requests::count();

         // 3. Calculate percentages safely (avoiding division by zero)
    $inProgressPercentage = $totalRequests > 0 ? round(($inProgressCount / $totalRequests) * 100) : 0;
    $postedPercentage = $totalRequests > 0 ? round(($postedCount / $totalRequests) * 100) : 0;
    $approvalPercentage = $totalRequests > 0 ? round(($approvalCount / $totalRequests) * 100) : 0;

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
            'users'
        ));
    }

    public function store(Request $request){

         // 1. Validate all form inputs matching your models
        $validatedData = $request->validate([
            'email' => 'required|email',
            'full_name' => 'required|string',
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
            } while (Photo_vid_requests::where('request_id', $randomRequestId)->exists());

         // 3. Save to the parent table (socmed_requests)
            $photoRequest = Photo_vid_requests::create([
                'request_id' => $randomRequestId,
                'email' => $validatedData['email'],
                'fullname' => $validatedData['full_name'],
                'department' => $validatedData['department'],
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
        ]);

        return redirect()->back()->with('success', 'Request updated successfully!');
   }
}
