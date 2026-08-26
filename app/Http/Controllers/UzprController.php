<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UzprController extends Controller
{
    //
    public function dashboard()
    {
        return view('admin.Creatives.uzpr.index');
    }
}
