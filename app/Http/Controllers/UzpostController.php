<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UzpostController extends Controller
{
    //
    public function dashboard()
    {
        return view('admin.Creatives.uzpost.index');
    }
}
