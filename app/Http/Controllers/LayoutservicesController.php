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
                $query->whereIn('status', [1, 4])->where('specification', 'layout'); // Adjust statuses as needed

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
            'specification_sod' => 'nullable|string',
            'category_sod' => 'nullable|string',
            'date_needed_socmed' => 'nullable|string'

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

            // 3. Save to the parent table (socmed_requests)
            $publishingRequest = Requestor::create([
                'request_id' => $requestId,
                'email' => $validatedData['email'],
                'f_name' => $validatedData['f_name'],
                'l_name' => $validatedData['l_name'],
                'middle' => $validatedData['middle'],
                'department' => $validatedData['department'],
            ]);

            // 4. Save to the child table (socmed_request_information)
            Publishing_request_informaitons::create([
                'request_id' => $requestId, // Links both tables together
                'purpose' => $validatedData['purpose'],
                'date_needed' => $validatedData['date_needed'],
                'category' => $validatedData['category'],
                'other_category' => $validatedData['other_category'] ?? null,
                'specification' => 'layout',
                'content_information' => $validatedData['content_info'] ?? null,
                'section' => $validatedData['section'],
                'approve' => $validatedData['approve'],
                'status' => '0', // Default status if applicable
                'reference' => $validatedData['content_attachment'] ?? null,
            ]);

            if ($request->input('needs_layout') == 1) {

                Socmed_request_information::create([
                    'request_id' => $requestId, // Links both tables together
                    'purpose' => $validatedData['purpose'],
                    'date_needed' => $validatedData['date_needed_socmed'] ?? $validatedData['date_needed'],
                    'category' => $validatedData['category_sod'] ?? $validatedData['category'],
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

    public function update(Request $request, $request_id)
    {
        DB::transaction(function () use ($request_id, $request) {
            $req = Publishing_request_informaitons::where('request_id', $request_id)->firstOrFail();

            $updateData = [];

            if ($request->has('produce')) {
                $updateData['produce'] = $request->input('produce') ?: null;
            }

            if ($request->filled('status')) {
                $updateData['status'] = $request->input('status');
                $updateData['date_approved'] = $request->input('status') == 1 ? now() : null;
            }

            if (! empty($updateData)) {
                $req->update($updateData);
            }
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

    public function markasDone(Request $request, $request_id)
    {
        $req = Publishing_request_informaitons::where('request_id', $request_id)->firstOrFail();

        // Update your status or database fields here
        $req->status = 1; // Done
        $req->save();

        SafeBroadcast::dispatch(SocmedRequestUpdated::class);
        return redirect()->back()->with('success', 'Request updated successfully!');
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
        $employeeId = auth()->user()->employee_id;
        abort_if(empty($employeeId), 403, 'Your account has no employee ID.');

        $req = Publishing_request_informaitons::where('request_id', $request_id)->firstOrFail();
        $req->update(['status' => 2, 'produce' => $employeeId]);

        SafeBroadcast::dispatch(SocmedRequestUpdated::class);
        return redirect()->back()->with('success', 'You took this task!');
    }

    public function assignTask(Request $request, $request_id)
    {
        $data = $request->validate([
            'user_assigned' => ['required', 'string'],
        ]);

        $req = Publishing_request_informaitons::where('request_id', $request_id)->firstOrFail();
        $req->update(['status' => 2, 'produce' => $data['user_assigned']]);

        SafeBroadcast::dispatch(SocmedRequestUpdated::class);
        return redirect()->back()->with('success', 'Request assigned successfully!');
    }
}
