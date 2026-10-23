<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SoftcopyController extends Controller
{
    //
    function dashboard()
    {
        return view('admin.softcopy.index');
    }
}
