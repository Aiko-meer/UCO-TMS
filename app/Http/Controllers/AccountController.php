<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    //
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
}
