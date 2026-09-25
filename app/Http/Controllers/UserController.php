<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Don't forget to import Hash

class UserController extends Controller
{
    public function dashboard()
    {
        $user = User::orderBy('id', 'desc')->get();
        return view('admin.account.user.users.index',compact('user'));
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
}