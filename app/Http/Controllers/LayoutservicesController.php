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
use Livewire\Component;

class LayoutservicesController extends Controller
{
    //
    public function dashboard()
    {
            $departments = Departments::all();
            $requests = Publishing_requests::with('information')
        ->whereHas('information', 
        function ($query) { $query->where('specification', 'layout'); })->orderBy('id', 'desc')->get();

        $totalRequests = Socmed_request::count();
            // 2. Get counts for each status
        // If 'status' is inside the related 'information' table:
            $inProgressCount = Publishing_requests::whereHas('information', function($q) {
                $q->where('status', 0); // Change to your actual in-progress status code
            })->count();

            $postedCount = Publishing_requests::whereHas('information', function($q) {
                $q->where('status', 2); // Posted status code
            })->count();

            $approvalCount = Publishing_requests::whereHas('information', function($q) {
                $q->where('status', 1); // Change to your actual approval status code (must be unique!)
            })->count();

            $monthCount = Publishing_requests::whereHas('information', function($q) {
                $q->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
            })->count();

            $totalCount = Publishing_requests::count();

            // Inside your controller method:
        $year = request('year', date('Y')); // Defaults to current year if not selected
        $month = request('month', date('m')); // Defaults to current month if not selected

        $archived = Publishing_requests::with('information')
            ->whereHas('information', function ($query) use ($year, $month) {
                $query->where('status', 2)
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month);
            })
            ->get();
            $actviepagi  = Publishing_requests::with('information')->paginate(5, ['fullname'], 'active_page');
        $archivepagi =Publishing_requests::with('information')->paginate(10);
        $monthpagi =Publishing_requests::with('information')->paginate(10);
        $listpagi =Publishing_requests::with('information')->paginate(5);

         // 3. Calculate percentages safely (avoiding division by zero)
    $inProgressPercentage = $totalRequests > 0 ? round(($inProgressCount / $totalRequests) * 100) : 0;
    $postedPercentage = $totalRequests > 0 ? round(($postedCount / $totalRequests) * 100) : 0;
    $approvalPercentage = $totalRequests > 0 ? round(($approvalCount / $totalRequests) * 100) : 0;

        return view('admin.creatives.layout.index', compact(
                'departments',
                'requests',
                'inProgressCount',
                'postedCount',
                'approvalCount',
                'monthCount',
                'totalCount',
                'archived',
                'actviepagi',
                'archivepagi',
                'monthpagi',
                'listpagi',
                'inProgressPercentage',
                'postedPercentage',
                'approvalPercentage'
            ));
    }

    public function store(Request $request)
    {
        // 1. Validate all form inputs matching your models
        $validatedData = $request->validate([
            'full_name' => 'required|string',
            'department' => 'required|string',
            'email' => 'required|email',
            'date_needed' => 'required|string',
            'section' => 'required|string',
            'category' => 'required|string',
            'other_category' => 'nullable|string',
            'purpose' => 'required|string',
            'content_info' => 'nullable|string',
            'content_attachment' => 'url',
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
                'other_category' =>$validatedData['other_category'],
                'specification' => 'layout',
                'content_information' => $validatedData['content_info'] ?? null,
                'section' => $validatedData['section'],
                'approve' => $validatedData['approve'],
                'status' => '0', // Default status if applicable
                'reference' => $validatedData['content_attachment'],
            ]);

            if ($request->input('needs_layout') == 1){

                $socmedrequest = Socmed_request::create([
                'request_id' => $randomRequestId,
                'email' => $validatedData['email'],
                'fullname' => $validatedData['full_name'],
                'department' => $validatedData['department'],
            ]);

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
    return redirect()->back()->with('success', 'Request saved successfully!');

} catch (\Exception $e) {
    DB::rollBack(); // Undo any partial database inserts if an error occurs
    return redirect()->back()->with('error', 'Failed to save: ' . $e->getMessage());
}
  }  
}
