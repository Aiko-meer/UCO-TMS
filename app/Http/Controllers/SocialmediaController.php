<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SocialmediaController extends Controller
{
    //
    public function dashboard()
    {
        return view('admin.socmed.index');
    }
}
