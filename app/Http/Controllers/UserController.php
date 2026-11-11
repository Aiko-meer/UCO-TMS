<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Requestor;
use App\Models\Soft_copy_requests;
use App\Models\TrademarkRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash; // Don't forget to import Hash
use App\Events\SocmedRequestUpdated;
use App\Support\SafeBroadcast;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function dashboard()
    {

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
            ));
    }

  public function store(Request $request)
        {
            // 1. Validate all form inputs (duplicates are rejected here, before saving)
            $validatedData = $request->validate([
                'emp_id'    => 'required|string|unique:users,employee_id',
                'name'      => 'required|string|unique:users,fullname',
                'password'  => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols()],
                'email'     => 'required|email|unique:users,email',
                'user_type' => 'required|string',
                'image'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ], [
                'emp_id.unique' => 'This Employee ID already exists.',
                'name.unique'   => 'This full name already exists.',
                'email.unique'  => 'This email already exists.',
            ]);

            DB::beginTransaction();

            try {
                $imagePath = null;

                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $imagePath = $file->storeAs('profile', $filename, 'public');
                }

                User::create([
                    'employee_id' => $validatedData['emp_id'],
                    'fullname'    => $validatedData['name'],
                    'password'    => Hash::make($validatedData['password']),
                    'email'       => $validatedData['email'],
                    'user_type'   => $validatedData['user_type'],
                    'image'       => $imagePath,
                    'verified'    => 0,
                ]);

                DB::commit();
                SafeBroadcast::dispatch(SocmedRequestUpdated::class);
                return redirect()->back()->with('success', 'User saved successfully!');

            } catch (\Exception $e) {
                DB::rollBack();
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
            SafeBroadcast::dispatch(SocmedRequestUpdated::class);
            return redirect()->back()->with('success', 'Password updated successfully!');
        }

        public function destroy($id)
        {
            $user = User::findOrFail($id);
            $user->delete();

            SafeBroadcast::dispatch(SocmedRequestUpdated::class);   
            return redirect()->back()->with('success', 'User deleted successfully!');
        }

        public function updateUserType(Request $request, User $user)
        {
            $validated = $request->validate([
                'user_type' => ['required', 'string', Rule::in(array_keys(config('user_types')))],
            ]);

            // Optional: stop admins from changing their own role by accident
            if (auth()->id() === $user->id) {
                return redirect()->back()->with('error', 'You cannot change your own user type.');
            }

            try {
                $user->update(['user_type' => $validated['user_type']]);

                return redirect()->back()->with('success', "User type updated for {$user->fullname}.");
            } catch (\Exception $e) {
                Log::error($e);
                return redirect()->back()->with('error', 'Failed to update user type.');
            }
        }
}