<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LayoutservicesController extends Controller
{
    //
    public function dashboard()
    {
        return view('admin.Creatives.layout.index');
    }
}
