<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PhotographyController extends Controller
{
    //
    public function dashboard()
    {
        return view('admin.photography.index');
    }
}
