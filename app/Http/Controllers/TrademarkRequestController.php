<?php

namespace App\Http\Controllers;
use App\Models\TrademarkRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class TrademarkRequestController extends Controller
{
    //
    function dashboard()
    {
        $softCopies = TrademarkRequest::latest()->get();
    return view('admin.trademark.index', compact(
            'softCopies'
    ));
    }

    public function store(Request $request)
    {
    // 1. Generate a unique 6-character random request ID first
        do {
            $randomRequestId = Str::random(6);
        } while (TrademarkRequest::where('request_id', $randomRequestId)->exists());    
    // 2. Validate incoming form inputs
        $validated = $request->validate([
            'salutation'             => 'required|string',
            'f_name'                 => 'required|string|max:255',
            'middle_name'            => 'nullable|string|max:255',
            'l_name'                 => 'required|string|max:255',
            'category_requestor'     => 'required|string',
            'position'               => 'required|string',
            'department_org'         => 'required|string',
            'contact_number'         => 'required|string',
            'email'                  => 'required|email',
            'office_address'         => 'required|string',
            'trademark_to_use'       => 'required|string',
            'date_to_use'            => 'required|date',
            'category_trademark_use' => 'required|string',
            'target_audience'        => 'required|string',
            'materials_media'        => 'required|string',
            'location_use'           => 'required|string',
            'purpose_of_use'         => 'required|string',
            'note'                   => 'nullable|string',
            'approved_by'            => 'nullable|string',
        ]);

        // 3. Default status (0 = Pending)
        $validated['status'] = 0;
        $validated['request_id'] = $randomRequestId;

        // 4. Create and save record to the database
        TrademarkRequest::create($validated);

        // 5. Redirect back with success alert
        return redirect()->back()->with('success', 'Trademark request submitted successfully!');
    }

     public function markAsSent(Request $request, $id)
        {
            $softCopy = TrademarkRequest::findOrFail($id);
            $softCopy->status = 1; // Approved / Sent
            $softCopy->save();

            return response()->json(['success' => true]);
        }

    public function markAsDenied(Request $request, $id)
        {
            $softCopy = TrademarkRequest::findOrFail($id);
            $softCopy->status = 2; // 2 = Rejected / Denied
            $softCopy->save();

            return response()->json(['success' => true]);
        }
}
