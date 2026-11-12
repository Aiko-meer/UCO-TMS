<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    public function dashboard()
    {
        return view('admin.account.index');
    }

    public function editprofile()
    {
        return view('admin.account.edit-profile');
    }

    public function editprofilepassword()
    {
        return view('admin.account.edit-account-password');
    }

    /**
     * Basic Information tab: full name and email address.
     */
    public function updateBasic(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email'    => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ], [
            'fullname.required' => 'Enter your full name.',
            'email.required'    => 'Enter your email address.',
            'email.email'       => 'Enter a valid email address.',
            'email.unique'      => 'That email address is already used by another account.',
        ]);

        $user->update($validated);

        return redirect()
            ->route('admin.account')
            ->with('success', 'Basic information updated.');
    }

    /**
     * Profile tab: portrait upload.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'image.required' => 'Choose a photo to upload.',
            'image.image'    => 'The selected file is not a valid image.',
            'image.mimes'    => 'Use a JPG or PNG image.',
            'image.max'      => 'The photo must be 2 MB or smaller.',
        ]);

        $user = $request->user();
        $file = $request->file('image');

        $filename = now()->format('YmdHis') . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('profile', $filename, 'public');

        // Remove the previous portrait once the new one is safely stored.
        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }

        $user->update(['image' => $path]);

        return redirect()
            ->route('admin.account.edit-profile')
            ->with('success', 'Profile photo updated.');
    }

    /**
     * Change Password tab.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => [
                'required',
                'string',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ],
        ], [
            'current_password.required' => 'Enter your current password.',
            'password.required'         => 'Enter a new password.',
            'password.confirmed'        => 'The new password confirmation does not match.',
        ]);

        $user = $request->user();

        if (! Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Your current password is incorrect.',
            ]);
        }

        if (Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'Choose a password different from your current one.',
            ]);
        }

        $user->update(['password' => $validated['password']]);

        // Rotate the session token after a credential change.
        $request->session()->regenerate();

        return redirect()
            ->route('admin.account.edit-password')
            ->with('success', 'Password changed successfully.');
    }
}
