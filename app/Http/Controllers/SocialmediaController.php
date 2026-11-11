<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requestor;
use App\Models\Departments;
use App\Models\Socmed_request_information;
use App\Models\Publishing_requests;
use App\Models\Publishing_request_informaitons;
use Illuminate\Support\Facades\DB;
use App\Models\Soft_copy_requests;
use App\Models\TrademarkRequest;
use Illuminate\Support\Str;
use App\Models\User;
use App\Events\SocmedRequestUpdated;
use App\Support\SafeBroadcast;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class SocialmediaController extends Controller
{
    //
    public function dashboard()
    {
        // Fetches parent records along with their corresponding child information

    $requests = Requestor::has('socmed')->orderBy('id', 'desc')->get();
    $users = User::where('user_type', 'content_team')->get();
    // Inside your controller method:
    $year = request('year', date('Y')); // Defaults to current year if not selected
    $month = request('month', date('m')); // Defaults to current month if not selected

    $archived = Requestor::with('socmed')
        ->whereHas('socmed', function ($query) use ($year, $month) {
            $query->where('status', 2)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month);
        })
        ->get();
        
    $totalRequests = Requestor::has('socmed')->count();

    // 2. Get counts for each status
    // If 'status' is inside the related 'information' table:
        $inProgressCount = Requestor::whereHas('socmed', function($q) {
            $q->where('status', 1); // Change to your actual in-progress status code
        })->count();

        $postedCount = Requestor::whereHas('socmed', function($q) {
            $q->where('status', 2); // Posted status code
        })->count();

        $approvalCount = Requestor::whereHas('socmed', function($q) {
            $q->where('status', 0); // Change to your actual approval status code (must be unique!)
        })->count();

        $monthCount = Requestor::whereHas('socmed', function($q) {
            $q->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
        })->count();

        $totalCount = Requestor::has('socmed')->count();

    // 3. Calculate percentages safely (avoiding division by zero)
    $inProgressPercentage = $totalRequests > 0 ? round(($inProgressCount / $totalRequests) * 100) : 0;
    $postedPercentage = $totalRequests > 0 ? round(($postedCount / $totalRequests) * 100) : 0;
    $approvalPercentage = $totalRequests > 0 ? round(($approvalCount / $totalRequests) * 100) : 0;
    // In your controller before returning the view
    //dd($requests->first()->information);
    return view('admin.socmed.index', compact('requests',
        'archived',
        'inProgressCount', 
        'inProgressPercentage', 
        'postedCount', 
        'postedPercentage', 
        'approvalCount', 
        'approvalPercentage',
        'totalCount',
        'monthCount',
        'users',
        ));
    }

   public function store(Request $request)
    {
    
        // 1. Validate all form inputs matching your models
        $validatedData = $request->validate([
            'email' => 'required|email',
            'f_name' => 'required|string',
            'l_name' => 'required|string',
            'middle' => 'nullable|string',
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
            'content_attachement' => 'nullable|url',
            'album_photo.*' => 'nullable|file|mimes:pdf,docx,jpeg,jpg,png|max:20480',
            'needs_layout' => 'nullable|string',

        ]);

        // The reference number is generated outside the transaction so the
        // exact value that is written to the database can be returned to the
        // requestor in the response.
        do {
            $requestId = Str::random(6);
        } while (Requestor::where('request_id', $requestId)->exists());

        try {
            // 2. Wrap saving in a database transaction
            DB::transaction(function () use ($request, $validatedData, $requestId) {

            // 1. Handle album_photo upload (the form posts album_photo[])
            if ($request->hasFile('album_photo')) {
                $files = $request->file('album_photo');
                $files = is_array($files) ? $files : [$files];
                $destinationPath = public_path('uploads/albums');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $albumPaths = [];
                foreach ($files as $file) {
                    $originalName = $file->getClientOriginalName();

                    if (file_exists($destinationPath . '/' . $originalName)) {
                        $originalName = time() . '_' . $originalName;
                    }

                    $file->move($destinationPath, $originalName);
                    $albumPaths[] = 'uploads/albums/' . $originalName;
                }

                $validatedData['album_photo'] = json_encode($albumPaths);
            } else {
                $validatedData['album_photo'] = null;
            }

            // 2. Handle reference files upload
            if ($request->hasFile('reference')) {
                $referencePaths = [];
                $destinationPath = public_path('uploads/reference');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                foreach ($request->file('reference') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $fileName = $originalName;
                    
                    if (file_exists($destinationPath . '/' . $fileName)) {
                        $fileName = time() . '_' . $originalName;
                    }
                    
                    $file->move($destinationPath, $fileName);
                    $referencePaths[] = 'uploads/reference/' . $fileName;
                }
                
                $validatedData['reference'] = json_encode($referencePaths);
            } else {
                $validatedData['reference'] = null;
            }

            // 3. Determine $produce value based on your exact business rules
            if ($request->hasFile('reference')) {
                // Rule A: If reference is not empty, produce must be null
                $produce = null;
            } elseif ($request->hasFile('album_photo') || $request->filled('content_attachement')) {
                // Rule B: If album_photo OR content_attachement is present, fill with full_name
                $produce = $validatedData['full_name'] ?? null;
            } else {
                // Default fallback
                $produce = null;
            }

            // 3. Save to the parent table (socmed_requests)
            $socmedRequest = Requestor::create([
                'request_id' => $requestId,
                'email' => $validatedData['email'],
                'f_name' => $validatedData['f_name'],
                'l_name' => $validatedData['l_name'],
                'middle' => $validatedData['middle'],
                'department' => $validatedData['department'],
            ]);

            // 4. Save to the child table (socmed_request_information)
            Socmed_request_information::create([
                'request_id' => $requestId, // Links both tables together
                'purpose' => $validatedData['purpose'],
                'date_needed' => $validatedData['date_needed'],
                'category' => $validatedData['category'],
                'specification' => $validatedData['specification'],
                'content_information' => $validatedData['content_information'] ?? null,
                'section' => $validatedData['section'] ?? null,
                'approve' => $validatedData['approve'] ?? null,
                'produce' => $produce,
                'album_photo' => $validatedData['album_photo'] ?? null,
                'published' => $validatedData['published'] ?? null,
                'status' => $validatedData['status'] ?? '0', // Default status if applicable
                'content_attachement' => $validatedData['content_attachement'] ?? null,
            ]);

            if ($request->input('needs_layout') == 1){

                Publishing_request_informaitons::create([
                'request_id' => $requestId, // Links both tables together
                'purpose' => $validatedData['purpose'],
                'date_needed' => $validatedData['date_needed'],
                'category' => $validatedData['category_layout'],
                'specification' => 'layout',
                'content_information' => $validatedData['content_information'] ?? null,
                'section' => $validatedData['section'] ?? null,
                'approve' => $validatedData['approve'] ?? null,
                'produce' => $validatedData['produce'] ?? null,
                'published' => $validatedData['published'] ?? null,
                'status' => $validatedData['status'] ?? '0', // Default status if applicable
                'reference' =>  $validatedData['reference'],
            ]);
            }
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

        // Find the main request record
       $req = Socmed_request_information::where('request_id', $request_id)->firstOrFail();

        // Update main request fields
        $req->update([
            'status' => $request->input('status'),
            'published' => $request->input('published'),
        ]);

        SafeBroadcast::dispatch(SocmedRequestUpdated::class);

        return redirect()->back()->with('success', 'Request updated successfully!');
   }

   public function markAsRead($id)
    {
        // Retired: this set one shared 'token' flag on the requestor row, which
        // cleared the badge for every account. Read state is now per account -
        // see NotificationController::read() and the notification_reads table.
        return response()->json([
            'success' => false,
            'message' => 'This endpoint is retired. Use /notifications/{key}/read.',
        ], 410);
    }

   public function destroy($request_id)
    {
        try {
            DB::transaction(function () use ($request_id) {
                
                // 1. Find the main record or fail
                $socmedRequest = Requestor::where('request_id', $request_id)->firstOrFail();
                
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
                
                // 5. Delete the parent record
                $socmedRequest->delete();
            });
           SafeBroadcast::dispatch(SocmedRequestUpdated::class);
            return redirect()->back()->with('success', 'Request and its attachments were deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete: ' . $e->getMessage());
        }
    }
}