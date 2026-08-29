<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Socmed_request;
use App\Models\Socmed_request_information;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SocialmediaController extends Controller
{
    //
    public function dashboard()
    {
        return view('admin.socmed.index');
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
        'specification' => 'required|string',
        'content_information' => 'nullable|string',
        'section' => 'nullable|string',
        'approve' => 'nullable|string',
        'produce' => 'nullable|string',
        'published' => 'nullable|string',
        'status' => 'nullable|string',
        'content_attachement.*' => 'nullable|file|mimes:pdf,docx,jpg,png|max:10240',
    ]);

    // 2. Wrap saving in a database transaction
    DB::transaction(function () use ($request, $validatedData) {
        
        // Handle multiple file uploads (matching your model's 'content_attechement' spelling)
       $attachmentPaths = [];
            if ($request->hasFile('content_attachement')) {
                // Sanitize the purpose field to make it safe for file names (remove spaces/special characters)
                $cleanPurpose = Str::slug($validatedData['purpose']);
                
                foreach ($request->file('content_attachement') as $index => $file) {
                    $extension = $file->getClientOriginalExtension();
                    
                    // Formulate name as: purpose-0.jpg, purpose-1.png, etc.
                    $filename = $cleanPurpose . '-' . $index . '.' . $extension;
                    
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
            'status' => $validatedData['status'] ?? '1', // Default status if applicable
            'content_attachement' => json_encode($attachmentPaths),
        ]);
    });

    return redirect()->back()->with('success', 'Your request has been successfully submitted!');
}
}