<?php

namespace App\Http\Controllers;

use App\Models\Socmed_request;
use App\Models\Departments;
use App\Models\User;
use App\Models\Socmed_request_information;
use App\Models\Publishing_request_informaitons;
use App\Models\Soft_copy_requests;
use App\Models\TrademarkRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Requestor;
use App\Events\SocmedRequestUpdated;
use App\Support\SafeBroadcast;

class LayoutservicesController extends Controller
{
    //
    public function dashboard()
    {

        $departments = Departments::all();
        $mytasks = Requestor::with('publishing')
            ->whereHas('publishing', function ($query) {
                $query->where('specification', 'layout')
                    ->whereIn('status', [2, 4]) // In Progress or Approved/Sent
                    ->where('produce', auth()->user()->employee_id); // Only tasks assigned to the logged-in user
            })
            ->orderBy('id', 'desc')
            ->get();
        $users = User::whereIn('user_type', ['creative_team', 'creative_admin'])
            ->withCount([
                'layoutRequests as in_progress_count' => function ($query) {
                    $query->where('status', 2)
                        ->where('specification', 'layout'); // Change 'specification' to your actual column name if it differs
                },
                'layoutRequests as in_pending_count' => function ($query) {
                    $query->where('status', 0)
                        ->where('specification', 'layout'); // Change 'specification' to your actual column name if it differs
                },
                'layoutRequests as approved_count' => function ($query) {
                    $query->where('status', 1)
                        ->where('specification', 'layout'); // Change 'specification' to your actual column name if it differs
                },
                'layoutRequests as total_count' => function ($query) {
                    $query->where('specification', 'layout'); // Change 'specification' to your actual column name if it differs
                }
            ])
            ->get();
        $requests = Requestor::with('publishing')
            ->whereHas(
                'publishing',
                function ($query) {
                    $query->where('specification', 'layout');
                }
            )->orderBy('id', 'desc')->get();

        $totalRequests = Requestor::whereHas('publishing', function ($q) {
            $q->where('specification', 'layout');
        })->count();
        // 2. Get counts for each status
        // If 'status' is inside the related 'information' table:
        $inProgressCount = Requestor::whereHas('publishing', function ($q) {
            $q->whereIn('status', [2, 4])
                ->where('specification', 'layout');
        })->count();

        $pendingCount = Requestor::whereHas('publishing', function ($q) {
            $q->where('status', 0)
                ->where('specification', 'layout');
        })->count();

        $approvalCount = Requestor::whereHas('publishing', function ($q) {
            $q->whereIn('status', [1, 3])
                ->where('specification', 'layout');
        })->count();

        $monthCount = Requestor::whereHas('publishing', function ($q) {
            $q->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('specification', 'layout');
        })->count();

        $totalCount =  Requestor::whereHas('publishing', function ($q) {
            $q->where('specification', 'layout');
        })->count();

        // Inside your controller method:
        $year = request('year');
        $month = request('month');

        $archived = Requestor::with('publishing')
            ->whereHas('publishing', function ($query) use ($year, $month) {
                $query->whereIn('status', [1, 4]); // Adjust statuses as needed

                if ($year) {
                    $query->whereYear('created_at', $year);
                }
                if ($month) {
                    $query->whereMonth('created_at', $month);
                }
            })
            ->get();
        $actviepagi  = Requestor::with('publishing')->paginate(5, ['*'], 'active_page');
        $archivepagi = Requestor::with('publishing')->paginate(10);
        $monthpagi = Requestor::with('publishing')->paginate(10);
        $listpagi = Requestor::with('publishing')->paginate(5);

        // 3. Calculate percentages safely (avoiding division by zero)
        $inProgressPercentage = $totalRequests > 0 ? round(($inProgressCount / $totalRequests) * 100) : 0;
        $pendingPercentage = $totalRequests > 0 ? round(($pendingCount / $totalRequests) * 100) : 0;
        $approvalPercentage = $totalRequests > 0 ? round(($approvalCount / $totalRequests) * 100) : 0;

        return view('admin.creatives.layout.index', compact(
            'departments',
            'requests',
            'inProgressCount',
            'pendingCount',
            'approvalCount',
            'monthCount',
            'totalCount',
            'archived',
            'actviepagi',
            'archivepagi',
            'monthpagi',
            'listpagi',
            'inProgressPercentage',
            'pendingPercentage',
            'approvalPercentage',
            'users',
            'mytasks'
        ));
    }

    public function store(Request $request)
    {
        // 1. Validate all form inputs matching your models
        $validatedData = $request->validate([
            'f_name' => 'required|string',
            'l_name' => 'required|string',
            'middle' => 'nullable|string',
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
            'specification_sod' => 'string',
            'category_sod' => 'string',
            'date_needed_socmed' => 'nullable|string'

        ]);

        // 2. Wrap saving in a database transaction
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
                'other_category' => $validatedData['other_category'],
                'specification' => 'layout',
                'content_information' => $validatedData['content_info'] ?? null,
                'section' => $validatedData['section'],
                'approve' => $validatedData['approve'],
                'status' => '0', // Default status if applicable
                'reference' => $validatedData['content_attachment'] ?? null,
            ]);

            if ($request->input('needs_layout') == 1) {

                Socmed_request_information::create([
                    'request_id' => $randomRequestId, // Links both tables together
                    'purpose' => $validatedData['purpose'],
                    'date_needed' => $validatedData['date_needed_socmed'],
                    'category' => $validatedData['category_sod'],
                    'specification' => $validatedData['specification_sod'],
                    'content_information' => $validatedData['content_info'] ?? null,
                    'section' => $validatedData['section'] ?? null,
                    'approve' => $validatedData['approve'] ?? null,
                    'produce' => $validatedData['produce'] ?? null,
                    'published' => $validatedData['published'] ?? null,
                    'status' => $validatedData['status'] ?? '0', // Default status if applicable
                ]);
            }
        });
        try {
            DB::commit(); // Finalize all inserts if everything succeeds
            SafeBroadcast::dispatch(SocmedRequestUpdated::class);
            return redirect()->back()->with('success', 'Request saved successfully!');
        } catch (\Exception $e) {
            DB::rollBack(); // Undo any partial database inserts if an error occurs

            return redirect()->back()->with('error', 'Failed to save: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $request_id)
    {
        // Wrap in a transaction to ensure both updates succeed together
        DB::transaction(function () use ($request_id, $request) {
            // Find the main request or fail with a 404
            $req = Publishing_request_informaitons::where('request_id', $request_id)->firstOrFail();

            // Updates the social media record if it exists
            Socmed_request_information::where('request_id', $request_id)->update([
                'produce' => $request->input('produce')
            ]);

            // Base update data
            $updateData = [
                'status' => $request->input('status'),
                'published' => $request->input('published'),
                'produce' => $request->input('produce'),
            ];

            // If status selected is 1, include the approval date
            if ($request->input('status') == 1) {
                $updateData['date_approved'] = now(); // or date('Y-m-d H:i:s')
            } else {
                $updateData['date_approved'] = null;
            }

            // Perform the update once with all applicable fields
            $req->update($updateData);
        });
        SafeBroadcast::dispatch(SocmedRequestUpdated::class);
        return redirect()->back()->with('success', 'Request updated successfully!');
    }

    public function markAsSent(Request $request, $request_id)
    {
        $req = Publishing_request_informaitons::where('request_id', $request_id)->firstOrFail();

        // Update your status or database fields here
        $req->status = 4; // Approved / Sent
        $req->save();

        SafeBroadcast::dispatch(SocmedRequestUpdated::class);
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }

    public function markAsDenied(Request $request, $request_id)
    {
        $softCopy = Publishing_request_informaitons::where('request_id', $request_id)->firstOrFail();
        $softCopy->status = 3; // 2 = Rejected / Denied
        $softCopy->date_approved = null;
        $softCopy->save();

        SafeBroadcast::dispatch(SocmedRequestUpdated::class);
        return response()->json(['success' => true]);
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
        DB::transaction(function () use ($request_id) {
            // 1. Delete from child and parent tables for publishing requests
            $publishingInfo = Publishing_request_informaitons::where('request_id', $request_id)->first();
            if ($publishingInfo) {
                // Optional: Delete physical files associated with reference/albums if stored locally
                // if ($publishingInfo->reference) { ... }
                $publishingInfo->delete();
            }

            Requestor::where('request_id', $request_id)->delete();

            // 2. Delete from child and parent tables for socmed requests (if they exist)
            $socmedInfo = Socmed_request_information::where('request_id', $request_id)->first();
            if ($socmedInfo) {
                $socmedInfo->delete();
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Request deleted successfully.'
        ]);

        // Or if you are redirecting back to a dashboard view:
        SafeBroadcast::dispatch(SocmedRequestUpdated::class);
        return redirect()->back()->with('success', 'Request deleted successfully.');
    }

    public function taketask(Request $request, $request_id)
    {
        // Wrap in a transaction to ensure both updates succeed together
        DB::transaction(function () use ($request_id, $request) {
            // Find the main request or fail with a 404
            $req = Publishing_request_informaitons::where('request_id', $request_id)->firstOrFail();

            // Updates the social media record if it exists
            Socmed_request_information::where('request_id', $request_id)->update([
                'produce' => $request->input('user')
            ]);

            // Base update data
            $updateData = [
                'status' => 2, // In Progress
                'produce' => $request->input('user'),
            ];

            // Perform the update once with all applicable fields
            $req->update($updateData);
        });
        SafeBroadcast::dispatch(SocmedRequestUpdated::class);
        return redirect()->back()->with('success', 'Request updated successfully!');
    }

     public function assignTask(Request $request, $request_id)
    {
        // Wrap in a transaction to ensure both updates succeed together
        DB::transaction(function () use ($request_id, $request) {
            // Find the main request or fail with a 404
            $req = Publishing_request_informaitons::where('request_id', $request_id)->firstOrFail();

            // Updates the social media record if it exists
            Socmed_request_information::where('request_id', $request_id)->update([
                'produce' => $request->input('user_assigned')
            ]);

            // Base update data
            $updateData = [
                'status' => 2, // In Progress
                'produce' => $request->input('user_assigned'),
            ];

            // Perform the update once with all applicable fields
            $req->update($updateData);
        });
        SafeBroadcast::dispatch(SocmedRequestUpdated::class);
        return redirect()->back()->with('success', 'Request updated successfully!');
    }
}
