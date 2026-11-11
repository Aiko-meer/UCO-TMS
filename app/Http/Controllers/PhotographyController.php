<?php

namespace App\Http\Controllers;

use App\Events\SocmedRequestUpdated;
use App\Models\Departments;
use App\Models\Photo_vid_request_informations;
use App\Models\Requestor;
use App\Models\User;
use App\Support\SafeBroadcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PhotographyController extends Controller
{
    //
    public function dashboard()
    {

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
        $mytasks = Requestor::withWhereHas('photo', function ($query) {
            $query->where('produce', auth()->user()->employee_id)
                ->whereIn('status', [1, 2]); // change to what you decide in point 1
        })
            ->latest('request_id')
            ->get();
        // Fetches parent records along with their corresponding child information
        $requests = Requestor::has('photo')
            ->whereHas('photo', function ($query) {
                $query->whereIn('category', ['photo', 'audio', 'soft copy']);
            })
            ->orderBy('id', 'desc')
            ->get();
        $departments = Departments::all();
        $actviepagi  = Requestor::with('photo')->paginate(5, ['*'], 'active_page');
        $archivepagi = Requestor::with('photo')->paginate(10);
        $monthpagi = Requestor::with('photo')->paginate(10);
        $listpagi = Requestor::with('photo')->paginate(5);
        $totalRequests = Requestor::count();
        // 2. Get counts for each status
        // If 'status' is inside the related 'information' table:
        $inProgressCount = Requestor::whereHas('photo', function ($q) {
            $q->where('status', 1); // Change to your actual in-progress status code
        })->count();

        $postedCount = Requestor::whereHas('photo', function ($q) {
            $q->where('status', 2); // Posted status code
        })->count();

        $approvalCount = Requestor::whereHas('photo', function ($q) {
            $q->where('status', 0); // Change to your actual approval status code (must be unique!)
        })->count();

        $monthCount = Requestor::whereHas('photo', function ($q) {
            $q->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        })->count();

        $totalCount = Requestor::whereHas('photo')->count();

        // 3. Calculate percentages safely (avoiding division by zero)
        $inProgressPercentage = $totalRequests > 0 ? round(($inProgressCount / $totalRequests) * 100) : 0;
        $postedPercentage = $totalRequests > 0 ? round(($postedCount / $totalRequests) * 100) : 0;
        $approvalPercentage = $totalRequests > 0 ? round(($approvalCount / $totalRequests) * 100) : 0;
        $requestors = Requestor::orderBy('l_name')->get();
        // null / empty = "All". On the very first visit (no URL value) use the current month and year.
        $month = request()->has('month') ? request('month') : now()->month;
        $year  = request()->has('year')  ? request('year')  : now()->year;

        $month = $month === null || $month === '' ? null : min(12, max(1, (int) $month));
        $year  = $year  === null || $year  === '' ? null : (int) $year;

        $archived = Requestor::with('photo')
            ->whereHas('photo', function ($q) use ($month, $year) {
                $q->where('status', 2)
                    ->when($year,  fn($q) => $q->whereYear('created_at', $year))
                    ->when($month, fn($q) => $q->whereMonth('created_at', $month));
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
            'requestors',
            'mytasks',
            'month',
            'year'
        ));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'email'       => 'required|email|max:255',
            'f_name'      => 'required|string|max:100',
            'l_name'      => 'required|string|max:100',
            'middle'      => 'nullable|string|max:100',
            'department'  => 'required|string|max:255',
            'event'       => 'required|string|max:255',
            'venue'       => 'required|string|max:255',
            'start'       => 'required|string|max:20',
            'end'         => 'required|string|max:20',
            'purpose_doc' => 'required|string|max:2000',
            'date_needed' => 'required|date',
            'category'    => 'required|string|max:50',
            'section'     => 'nullable|string|max:255',
            'approve'     => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($validatedData) {
                do {
                    $randomRequestId = Str::random(6);
                } while (Requestor::where('request_id', $randomRequestId)->exists());

                Requestor::create([
                    'request_id' => $randomRequestId,
                    'email'      => $validatedData['email'],
                    'f_name'     => $validatedData['f_name'],
                    'l_name'     => $validatedData['l_name'],
                    'middle'     => $validatedData['middle'] ?? null,
                    'department' => $validatedData['department'],
                ]);

                Photo_vid_request_informations::create([
                    'request_id'       => $randomRequestId,
                    'date_needed'      => $validatedData['date_needed'],
                    'category'         => $validatedData['category'],
                    'section'          => $validatedData['section'] ?? null,
                    'event'            => $validatedData['event'],
                    'venue'            => $validatedData['venue'],
                    'start_time'       => $validatedData['start'],
                    'end_time'         => $validatedData['end'],
                    'purpose_document' => $validatedData['purpose_doc'],
                    'approve'          => $validatedData['approve'],
                    'status'           => 0,
                ]);
            });

            SafeBroadcast::dispatch(SocmedRequestUpdated::class);

            return redirect()->back()->with('success', 'Request saved successfully!');
        } catch (\Throwable $e) {
            report($e);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Could not save the request. Please try again.');
        }
    }

    public function update(Request $request, $request_id)
    {
        $role    = auth()->user()->user_type;
        $isAdmin = in_array($role, ['admin', 'superadmin'], true);

        // Content team cannot use this; they use Take Task
        abort_unless($isAdmin || $role === 'content_admin', 403, 'You are not allowed to update this request.');

        $req = Photo_vid_request_informations::where('request_id', $request_id)->firstOrFail();

        $data = $request->validate([
            'status'  => 'sometimes|nullable|integer|in:0,1,2',
            'produce' => [
                'nullable',
                Rule::exists('users', 'employee_id')
                    ->whereIn('user_type', ['content_team', 'content_admin']),
            ],
        ]);

        if (array_key_exists('status', $data)) {
            if ($data['status'] === null) {
                unset($data['status']);                      // ignore empty status
            } elseif (! $isAdmin) {
                if ((int) $data['status'] !== (int) $req->status) {
                    abort(403, 'Only an admin can change the status.');
                }
                unset($data['status']);                      // same value, nothing to change
            }
        }

        $req->update($data);

        SafeBroadcast::dispatch(SocmedRequestUpdated::class);

        return redirect()->back()->with('success', 'Request updated successfully!');
    }

    public function destroy($request_id)
    {
        $role = auth()->user()->user_type;
        abort_unless(in_array($role, ['admin', 'superadmin', 'content_admin'], true), 403);

        try {
            DB::transaction(function () use ($request_id) {
                $photoRequest = Requestor::where('request_id', $request_id)->firstOrFail();
                Photo_vid_request_informations::where('request_id', $request_id)->delete();
                $photoRequest->delete();
            });

            SafeBroadcast::dispatch(SocmedRequestUpdated::class);

            return redirect()->back()->with('success', 'Request deleted successfully!');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->with('error', 'Could not delete the request.');
        }
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

    public function markAsDenied(Request $request, $id)
    {
        $softCopy = Soft_copy_requests::findOrFail($id);
        $softCopy->status = 2; // 2 = Rejected / Denied
        $softCopy->save();

        return response()->json(['success' => true]);
    }

    public function taketask($request_id)
    {
        $employeeId = auth()->user()->employee_id;
        abort_if(empty($employeeId), 403, 'Your account has no employee ID.');

        // One atomic query: only succeeds if nobody has taken it yet
        $taken = Photo_vid_request_informations::query()
            ->where('request_id', $request_id)
            ->where(fn($q) => $q->whereNull('produce')->orWhere('produce', ''))
            ->update([
                'produce' => $employeeId,   // always YOUR own ID, never from the form
                'status'  => 1,
            ]);

        if ($taken === 0) {
            return redirect()->back()->with('error', 'This task was already taken or does not exist.');
        }

        SafeBroadcast::dispatch(SocmedRequestUpdated::class);

        return redirect()->back()->with('success', 'You took this task!');
    }

}