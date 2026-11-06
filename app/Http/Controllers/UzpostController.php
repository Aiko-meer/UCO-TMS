<?php

namespace App\Http\Controllers;

use App\Models\Departments;
use App\Models\Publishing_request_informaitons;
use App\Models\Requestor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Events\SocmedRequestUpdated;
use App\Support\SafeBroadcast;

/**
 * UZ Post dashboard.
 *
 * This used to query App\Models\Publishing_requests, which does not exist, so
 * the page threw "Class not found" and every notification pointing at a UZ Post
 * request landed on a 500. Publishing requests are Requestor rows whose child
 * row has specification 'uzpost'.
 */
class UzpostController extends Controller
{
    private const SPECIFICATION = 'uzpost';

    public function dashboard()
    {
        $departments = Departments::all();

        $base = fn () => Requestor::with('publishing')
            ->whereHas('publishing', function ($query) {
                $query->where('specification', self::SPECIFICATION);
            });

        $requests = $base()->orderBy('id', 'desc')->get();

        $users = User::whereIn('user_type', ['creative_team', 'creative_admin'])
            ->withCount([
                'layoutRequests as in_progress_count' => function ($query) {
                    $query->where('status', 2)->where('specification', self::SPECIFICATION);
                },
                'layoutRequests as in_pending_count' => function ($query) {
                    $query->where('status', 0)->where('specification', self::SPECIFICATION);
                },
                'layoutRequests as approved_count' => function ($query) {
                    $query->where('status', 1)->where('specification', self::SPECIFICATION);
                },
                'layoutRequests as total_count' => function ($query) {
                    $query->where('specification', self::SPECIFICATION);
                },
            ])
            ->get();

        $year = request('year', date('Y'));
        $month = request('month', date('m'));

        $archived = $base()
            ->whereHas('publishing', function ($query) use ($year, $month) {
                $query->where('status', 2)
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month);
            })
            ->get();

        $totalRequests = $base()->count();

        $inProgressCount = $base()->whereHas('publishing', fn ($q) => $q->where('status', 2))->count();
        $pendingCount = $base()->whereHas('publishing', fn ($q) => $q->where('status', 0))->count();
        $approvalCount = $base()->whereHas('publishing', fn ($q) => $q->where('status', 1))->count();

        $monthCount = $base()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $totalCount = $totalRequests;

        $inProgressPercentage = $totalRequests > 0 ? round(($inProgressCount / $totalRequests) * 100) : 0;
        $pendingPercentage = $totalRequests > 0 ? round(($pendingCount / $totalRequests) * 100) : 0;
        $approvalPercentage = $totalRequests > 0 ? round(($approvalCount / $totalRequests) * 100) : 0;

        return view('admin.Creatives.uzpost.index', compact(
            'departments',
            'requests',
            'users',
            'archived',
            'totalRequests',
            'inProgressCount',
            'pendingCount',
            'approvalCount',
            'inProgressPercentage',
            'pendingPercentage',
            'approvalPercentage',
            'monthCount',
            'totalCount',
        ));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'f_name' => 'required|string',
            'l_name' => 'required|string',
            'middle' => 'nullable|string',
            'department' => 'required|string',
            'email' => 'required|email',
            'date_needed' => 'required|string',
            'section' => 'required|string',
            'purpose' => 'required|string',
            'content_attachment' => 'required|url',
            'approve' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            do {
                $randomRequestId = Str::random(6);
            } while (Requestor::where('request_id', $randomRequestId)->exists());

            

            Requestor::create([
                'request_id' => $randomRequestId,
                'email' => $validatedData['email'],
                'f_name' => $validatedData['f_name'],
                'l_name' => $validatedData['l_name'],
                'middle' => $validatedData['middle'],
                'department' => $validatedData['department'],
            ]);

            Publishing_request_informaitons::create([
                'request_id' => $randomRequestId,
                'purpose' => $validatedData['purpose'],
                'date_needed' => $validatedData['date_needed'],
                'specification' => self::SPECIFICATION,
                'section' => $validatedData['section'],
                'approve' => $validatedData['approve'],
                'status' => '0',
                'reference' => $validatedData['content_attachment'] ?? null,
            ]);

            DB::commit();

            // Broadcast after the commit so a down realtime server can never
            // report this successful save as a failure.
            SafeBroadcast::dispatch(SocmedRequestUpdated::class);

            return redirect()->back()->with('success', 'Request saved successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Failed to save: '.$e->getMessage());
        }
    }

    public function update(Request $request, $request_id)
    {
        DB::beginTransaction();

        try {
            $req = Publishing_request_informaitons::where('request_id', $request_id)->firstOrFail();

            $req->update([
                'status' => $request->input('status'),
                'produce' => $request->input('produce'),
            ]);

            DB::commit();

            SafeBroadcast::dispatch(SocmedRequestUpdated::class);

            return redirect()->back()->with('success', 'Request updated successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Failed to update: '.$e->getMessage());
        }
    }
}
