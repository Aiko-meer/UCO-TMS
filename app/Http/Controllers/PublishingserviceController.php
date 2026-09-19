<?php

namespace App\Http\Controllers;
use App\Models\Socmed_request;
use App\Models\Departments;
use App\Models\Socmed_request_information;
use App\Models\Publishing_requests;
use App\Models\Publishing_request_informaitons;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PublishingserviceController extends Controller
{
    //
     public function dashboard()
    {
        return view('admin.Creatives.publishing.index');
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
            'category_sod' => 'string',
            'specification_sod' => 'string',
            'date_needed_socmed' => 'string',
            'other_category' => 'nullable|string',
            'content_caption' => 'nullable|string',
            'content_attachement.*' => 'nullable|file|mimes:pdf,docx,jpg,png|max:10240',
            'content_information' => 'nullable|string',
            'section' => 'nullable|string',
            'approve' => 'nullable|string',
        ]);

       // 2. Wrap saving in a database transaction
        DB::transaction(function () use ($request, $validatedData) {
            
            // Handle multiple file uploads (matching your model's 'content_attechement' spelling)
        $attachmentPaths = [];
                        if ($request->hasFile('content_attachement')) {
                            $cleanPurpose = Str::slug($validatedData['purpose']);
                            $produce = $validatedData['full_name'];
                            
                            foreach ($request->file('content_attachement') as $file) {
                                $originalName = $file->getClientOriginalName();
                                // Prefixes with unique timestamp + purpose + original filename to prevent collisions
                                $filename = time() . '-' . $cleanPurpose . '-' . $originalName;
                                
                                $path = $file->storeAs('layout', $filename, 'public');
                            }

                            
                        }else{
                            $produce = "";
                        }

            // Generate a unique 6-character random request ID
            do {
                $randomRequestId = Str::random(6);
            } while (Socmed_request::where('request_id', $randomRequestId)->exists());

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
                'specification' => 'layout',
                'content_information' => $validatedData['content_information'] ?? null,
                'section' => $validatedData['section'] ?? null,
                'approve' => $validatedData['approve'] ?? null,
                'produce' => $produce,
                'published' => $validatedData['published'] ?? null,
                'status' => $validatedData['status'] ?? '0', // Default status if applicable
                'content_attachement' => json_encode($attachmentPaths),
            ]);

            if ($request->input('needs_layout') == 1){

                $socmedRequest = Socmed_requests::create([
                'request_id' => $randomRequestId,
                'email' => $validatedData['email'],
                'fullname' => $validatedData['full_name'],
                'department' => $validatedData['department'],
            ]);

                Socmed_request_informaitons::create([
                'request_id' => $randomRequestId, // Links both tables together
                'purpose' => $validatedData['purpose'],
                'date_needed' => $validatedData['date_needed_sod'],
                'category' => $validatedData['category_sod'],
                'specification' => $validatedData['specification_sod'],
                'content_information' => $validatedData['content_caption'] ?? null,
                'section' => $validatedData['section'] ?? null,
                'approve' => $validatedData['approve'] ?? null,
                'produce' => $validatedData['produce'] ?? null,
                'published' => $validatedData['published'] ?? null,
                'status' => $validatedData['status'] ?? '0', // Default status if applicable
            ]);
            }
        });

  }      
}
