<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Socmed_request;
use App\Models\Photo_vid_requests;
use App\Models\Departments;
use App\Models\Socmed_request_information;
use App\Models\Publishing_requests;
use App\Models\Publishing_request_informaitons;
use Illuminate\Support\Facades\DB;
use App\Models\Soft_copy_requests;
use App\Models\TrademarkRequest;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class SocialmediaController extends Controller
{
    //
    public function dashboard()
    {
        // Fetches parent records along with their corresponding child information
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
    $requests = Socmed_request::with('information')->orderBy('id', 'desc')->get();;
   $actviepagi  = Socmed_request::with('information')->paginate(5, ['fullname'], 'active_page');
    $archivepagi =Socmed_request::with('information')->paginate(10);
    $monthpagi =Socmed_request::with('information')->paginate(10);
    $listpagi =Socmed_request::with('information')->paginate(5);
    $departments = Departments::all(); 
    $users = User::where('user_type', 'content_team')->get();
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
            $q->where('status', 1); // Change to your actual in-progress status code
        })->count();

        $postedCount = Socmed_request::whereHas('information', function($q) {
            $q->where('status', 2); // Posted status code
        })->count();

        $approvalCount = Socmed_request::whereHas('information', function($q) {
            $q->where('status', 0); // Change to your actual approval status code (must be unique!)
        })->count();

        $monthCount = Socmed_request::whereHas('information', function($q) {
            $q->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
        })->count();

        $totalCount = Socmed_request::count();

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
        'approvalPercentage',
        'totalCount',
        'actviepagi',
        'archivepagi',
        'monthpagi',
        'listpagi',
        'monthCount',
        'users',
        'socmed',
        'photovid',
        'layout',
        'totalNotifications',
        'production',
        'uzpost',
        'uzpr',
        'softcopy',
        'trade'
        ));
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
                            $produce = $validatedData['full_name'];
                            
                            foreach ($request->file('content_attachement') as $file) {
                                $originalName = $file->getClientOriginalName();
                                // Prefixes with unique timestamp + purpose + original filename to prevent collisions
                                $filename = time() . '-' . $cleanPurpose . '-' . $originalName;
                                
                                $path = $file->storeAs('attachments', $filename, 'public');
                                $attachmentPaths[] = $path;
                            }

                            
                        }else{
                            $produce = "";
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
                'token' => '0',
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
                'produce' => $produce,
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
   DB::commit(); // Finalize all inserts if everything succeeds
    return redirect()->back()->with('success', 'Request saved successfully!');

} catch (\Exception $e) {
    DB::rollBack(); // Undo any partial database inserts if an error occurs
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

   public function markAsRead($id)
    {
        $socmed = Socmed_request::findOrFail($id);
        $socmed->token = '1'; // or 1 depending on your column type
        $socmed->save();

        return response()->json(['success' => true]);
    }

   public function destroy($request_id)
    {
        try {
            DB::transaction(function () use ($request_id) {
                
                // 1. Find the main record or fail
                $socmedRequest = Socmed_request::where('request_id', $request_id)->firstOrFail();
                
                // 2. Find the information record to retrieve file paths
                $socmedInfo = Socmed_request_information::where('request_id', $request_id)->first();
                
                // 3. Delete physical files from the storage disk
                if ($socmedInfo && $socmedInfo->content_attachement) {
                    $attachments = json_decode($socmedInfo->content_attachement, true);
                    if (is_array($attachments)) {
                        foreach ($attachments as $path) {
                            if (Storage::disk('public')->exists($path)) {
                                Storage::disk('public')->delete($path);
                            }
                        }
                    }
                }

                // 4. Delete related records across all tables linked by request_id
                Socmed_request_information::where('request_id', $request_id)->delete();
                Publishing_request_informaitons::where('request_id', $request_id)->delete();
                Publishing_requests::where('request_id', $request_id)->delete();
                
                // 5. Delete the parent record
                $socmedRequest->delete();
            });

            return redirect()->back()->with('success', 'Request and its attachments were deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete: ' . $e->getMessage());
        }
    }
}