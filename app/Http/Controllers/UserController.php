<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Publishing_requests;
use App\Models\Photo_vid_requests;
use App\Models\Socmed_request;
use App\Models\Soft_copy_requests;
use App\Models\TrademarkRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash; // Don't forget to import Hash

class UserController extends Controller
{
    public function dashboard()
    {
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
        // Fetch users with dynamic task counts per category
            $user = User::withCount([
                'layoutRequests as uzpr_count' => function ($query) {
                    $query->where('specification', 'uzpr')->where('status', '1');
                },
                'layoutRequests as uzpost_count' => function ($query) {
                    $query->where('specification', 'uzpost')->where('status', '1');
                },
                'photoRequests as coverage_count' => function ($query) {
                    $query->where('status', '1');
                },
                'layoutRequests as layout_count' => function ($query) {
                    $query->where('specification', 'layout')->where('status', '1');
                },
                'layoutRequests as publication_count' => function ($query) {
                    $query->where('specification', 'publication')->where('status', '1');
                },
            ])
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.account.user.users.index',compact(
            'user',
            'socmed',
            'photovid',
            'layout',
            'production',
            'uzpost',
            'uzpr',
            'totalNotifications',
            'softcopy',
            'trade'
            ));
    }

    public function store(Request $request)
    {
        // 1. Validate all form inputs
        $validatedData = $request->validate([
            'emp_id' => 'required|string',
            'name' => 'required|string',
            'password' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'user_type' => 'required|string',
            'image' => 'nullable|file|mimes:pdf,docx,jpg,png|max:10240',
        ]);

        DB::beginTransaction();

        try {
            $imagePath = null;

            // 2. Handle single file upload if present
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $imagePath = $file->storeAs('profile', $filename, 'public');
            }

            // 3. Save to the database
            User::create([
                'employee_id' => $validatedData['emp_id'],
                'fullname' => $validatedData['name'],
                'password' => Hash::make($validatedData['password']), // Securely hash the temporary password
                'email' => $validatedData['email'],
                'user_type' => $validatedData['user_type'],
                'image' => $imagePath, // Save the file path string
                'verified' => 0,
            ]);

            DB::commit(); // Finalize database inserts if everything succeeds
            return redirect()->back()->with('success', 'User saved successfully!');

        } catch (\Exception $e) {
            DB::rollBack(); // Undo database inserts if an error occurs
            return redirect()->back()->with('error', 'Failed to save: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
        {
            // 1. Find the user first so we can check their current password
            $user = User::findOrFail($id);

            // 2. Validate with custom check for current password repetition
            $request->validate([
                'password' => [
                    'required',
                    'string',
                    Password::min(8)
                        ->mixedCase() // Requires uppercase & lowercase
                        ->numbers()   // Requires numbers
                        ->symbols(),  // Requires special characters
                    'confirmed',  // Automatically looks for 'password_confirmation' field
                    
                    // Custom rule to ensure new password !== old password
                    function ($attribute, $value, $fail) use ($user) {
                        if (Hash::check($value, $user->password)) {
                            $fail('The new password cannot be the same as your current password.');
                        }
                    },
                ],
            ], [
                'password.confirmed' => 'The password confirmation does not match.',
            ]);

            // 3. Update the password and save
            $user->password = Hash::make($request->password);
            $user->save();

            // 4. Return back with a success message
            return redirect()->back()->with('success', 'Password updated successfully!');
        }

        public function destroy($id)
        {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->back()->with('success', 'User deleted successfully!');
        }
}