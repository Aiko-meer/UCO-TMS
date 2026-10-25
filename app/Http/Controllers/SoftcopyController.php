<?php

namespace App\Http\Controllers;
use App\Models\Soft_copy_requests;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SoftcopyController extends Controller
{
    //
    function dashboard()
    {
        // 1. Fetch all records from the database (using 'latest()' sorts them by newest first)
         $softCopies = Soft_copy_requests::latest()->get();
         $users = User::where('user_type', 'yearbook_team')->get();
        // 2. Get counts for each status
       // 1. Pending (In-progress) count where status is 0
        $inProgressCount = Soft_copy_requests::where('status', 0)->count();

        // 2. Done (Approved) count where status is 1
        $postedCount = Soft_copy_requests::where('status', 1)->count();
        $denyCount = Soft_copy_requests::where('status', 2)->count();


        $totalCount = Soft_copy_requests::count();

    // 3. Calculate percentages safely (avoiding division by zero)
    $inProgressPercentage = $totalCount > 0 ? round(($inProgressCount / $totalCount) * 100) : 0;
    $postedPercentage = $totalCount > 0 ? round(($postedCount / $totalCount) * 100) : 0;
     $denyPercentage = $totalCount > 0 ? round(($denyCount / $totalCount) * 100) : 0;

        // 2. Pass the data into the view using compact()
        return view('admin.softcopy.index', compact(
            'softCopies',
            'inProgressPercentage',
            'postedPercentage',
            'inProgressCount',
            'postedCount',
            'denyCount',
            'denyPercentage',
            'users'
            ));
    }

    function store(Request $request)
    {
       // 1. Generate a unique 6-character random request ID first
        do {
            $randomRequestId = Str::random(6);
        } while (Soft_copy_requests::where('request_id', $randomRequestId)->exists());

        // 2. Validate only the user inputs coming from the form
        $validatedData = $request->validate([
            'f_name'        => 'required|string|max:255',
            'middle_name'   => 'nullable|string|max:255',
            'l_name'        => 'required|string|max:255',
            'email'         => ['required', 'email', 'regex:/@uz\.edu\.ph$/'],
            'number'        => 'required|string|max:20',
            'is_graduating' => 'required|in:Yes,No',
            'school_level'  => 'required|string',
            'program'       => 'required|string|max:255',
            'year_level'    => 'required|string',
            'grad'          => 'nullable|string|max:10',
            'date_photo'    => 'required|date',
            'id_number'     => 'required|string',
            'id_attachment' => 'required|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        // 3. Handle file upload safely without spaces or special chars
            if ($request->hasFile('id_attachment')) {
                $file = $request->file('id_attachment');
                
                // Clean the original filename (removes spaces, special characters, dots)
                $cleanOriginalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $cleanOriginalName = Str::slug($cleanOriginalName);
                
                // Generate a safe, clean filename
                $filename = time() . '-' . Str::slug($validatedData['l_name']) . '-' . $cleanOriginalName . '.' . $file->getClientOriginalExtension();
                
                // Store in storage/app/public/id_attachments
                $path = $file->storeAs('id_attachments', $filename, 'public');
                
                // Force override $validatedData['id_attachment'] with the string path
                $validatedData['id_attachment'] = $path;
            }

        // 4. Inject your server-generated fields into the array
        $validatedData['request_id'] = $randomRequestId;
        $validatedData['status']     = '0';

        // 5. Save everything to the database
        Soft_copy_requests::create($validatedData);

        return redirect()->back()->with('success', 'Soft copy request submitted successfully!');
            }

        public function markAsSent(Request $request, $id)
        {
            $softCopy = Soft_copy_requests::findOrFail($id);
            $softCopy->status = 1; // Approved / Sent
            $softCopy->save();

            return response()->json(['success' => true]);
        }

        public function markAsDenied(Request $request, $id)
        {
            $softCopy = Soft_copy_requests::findOrFail($id);
            $softCopy->status = 2; // 2 = Rejected / Denied
            $softCopy->save();

            return response()->json(['success' => true]);
        }
}
