<?php

namespace App\Http\Controllers;
use App\Models\Departments;
use App\Models\Photo_vid_requests;
use App\Models\Photo_vid_request_informations;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PhotographyController extends Controller
{
    //
    public function dashboard()
    {
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
            'listpagi'
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
