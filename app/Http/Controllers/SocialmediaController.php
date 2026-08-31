<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Socmed_request;
use App\Models\Departments;
use App\Models\Socmed_request_information;
use App\Models\Publishing_requests;
use App\Models\Publishing_request_informaitons;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SocialmediaController extends Controller
{
    //
    public function dashboard()
    {
        // Fetches parent records along with their corresponding child information
    $requests = Socmed_request::with('information')->get();
    $departments = Departments::all(); 
    // Inside your controller method:
    $year = request('year', date('Y')); // Defaults to current year if not selected
    $month = request('month', date('m')); // Defaults to current month if not selected

    $archived = Socmed_request::with('information')
        ->whereHas('information', function ($query) use ($year, $month) {
            $query->where('status', 2)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month);
        })
        ->get();
        
    $totalRequests = Socmed_request::count();

    // 2. Get counts for each status
    // If 'status' is inside the related 'information' table:
        $inProgressCount = Socmed_request::whereHas('information', function($q) {
            $q->where('status', 0); // Change to your actual in-progress status code
        })->count();

        $postedCount = Socmed_request::whereHas('information', function($q) {
            $q->where('status', 2); // Posted status code
        })->count();

        $approvalCount = Socmed_request::whereHas('information', function($q) {
            $q->where('status', 1); // Change to your actual approval status code (must be unique!)
        })->count();

    // 3. Calculate percentages safely (avoiding division by zero)
    $inProgressPercentage = $totalRequests > 0 ? round(($inProgressCount / $totalRequests) * 100) : 0;
    $postedPercentage = $totalRequests > 0 ? round(($postedCount / $totalRequests) * 100) : 0;
    $approvalPercentage = $totalRequests > 0 ? round(($approvalCount / $totalRequests) * 100) : 0;
    // In your controller before returning the view
    //dd($requests->first()->information);
    return view('admin.socmed.index', compact('requests',
        'departments',
        'archived',
        'inProgressCount', 
        'inProgressPercentage', 
        'postedCount', 
        'postedPercentage', 
        'approvalCount', 
        'approvalPercentage'));
    }

   public function store(Request $request)
    {
    
        // 1. Validate all form inputs matching your models
        $validatedData = $request->validate([
            'email' => 'required|email',
            'full_name' => 'required|string',
            'department' => 'required|string',
            'purpose' => 'required|string',
            'date_needed' => 'required|string',
            'category' => 'required|string',
            'category_layout' => 'nullable|string',
            'reference.*' => 'nullable|file|mimes:pdf,docx,jpg,png|max:10240',
            'specification' => 'required|string',
            'content_information' => 'nullable|string',
            'section' => 'nullable|string',
            'approve' => 'nullable|string',
            'produce' => 'nullable|string',
            'published' => 'nullable|string',
            'status' => 'nullable|string',
            'content_attachement.*' => 'nullable|file|mimes:pdf,docx,jpg,png|max:10240',
            'needs_layout' => 'nullable|string',

        ]);

        // 2. Wrap saving in a database transaction
        DB::transaction(function () use ($request, $validatedData) {
            
            // Handle multiple file uploads (matching your model's 'content_attechement' spelling)
        $attachmentPaths = [];
                        if ($request->hasFile('content_attachement')) {
                            $cleanPurpose = Str::slug($validatedData['purpose']);
                            
                            foreach ($request->file('content_attachement') as $file) {
                                $originalName = $file->getClientOriginalName();
                                // Prefixes with unique timestamp + purpose + original filename to prevent collisions
                                $filename = time() . '-' . $cleanPurpose . '-' . $originalName;
                                
                                $path = $file->storeAs('attachments', $filename, 'public');
                                $attachmentPaths[] = $path;
                            }
                        }

            // Generate a unique 6-character random request ID
            do {
                $randomRequestId = Str::random(6);
            } while (Socmed_request::where('request_id', $randomRequestId)->exists());

            // 3. Save to the parent table (socmed_requests)
            $socmedRequest = Socmed_request::create([
                'request_id' => $randomRequestId,
                'email' => $validatedData['email'],
                'fullname' => $validatedData['full_name'],
                'department' => $validatedData['department'],
            ]);

            // 4. Save to the child table (socmed_request_information)
            Socmed_request_information::create([
                'request_id' => $randomRequestId, // Links both tables together
                'purpose' => $validatedData['purpose'],
                'date_needed' => $validatedData['date_needed'],
                'category' => $validatedData['category'],
                'specification' => $validatedData['specification'],
                'content_information' => $validatedData['content_information'] ?? null,
                'section' => $validatedData['section'] ?? null,
                'approve' => $validatedData['approve'] ?? null,
                'produce' => $validatedData['produce'] ?? null,
                'published' => $validatedData['published'] ?? null,
                'status' => $validatedData['status'] ?? '0', // Default status if applicable
                'content_attachement' => json_encode($attachmentPaths),
            ]);

            if ($request->input('needs_layout') == 1){

                $publishingRequest = Publishing_requests::create([
                'request_id' => $randomRequestId,
                'email' => $validatedData['email'],
                'fullname' => $validatedData['full_name'],
                'department' => $validatedData['department'],
            ]);

                Publishing_request_informaitons::create([
                'request_id' => $randomRequestId, // Links both tables together
                'purpose' => $validatedData['purpose'],
                'date_needed' => $validatedData['date_needed'],
                'category' => $validatedData['category_layout'],
                'specification' => 'layout',
                'content_information' => $validatedData['content_information'] ?? null,
                'section' => $validatedData['section'] ?? null,
                'approve' => $validatedData['approve'] ?? null,
                'produce' => $validatedData['produce'] ?? null,
                'published' => $validatedData['published'] ?? null,
                'status' => $validatedData['status'] ?? '1', // Default status if applicable
                'reference' => json_encode($attachmentPaths),
            ]);
            }
        });

       try {
    // Your save/create logic here
    Socmed_request::create($request->all());

    return redirect()->back()->with('success', 'Request saved successfully!');
} catch (\Exception $e) {
    return redirect()->back()->with('error', 'Failed to save: ' . $e->getMessage());
}
    }

   public function update(Request $request, $request_id) {

        // Find the main request record
       $req = Socmed_request_information::where('request_id', $request_id)->firstOrFail();

        // Update main request fields
        $req->update([
            'status' => $request->input('status'),
            'published' => $request->input('published'),
        ]);

        return redirect()->back()->with('success', 'Request updated successfully!');
   }
}