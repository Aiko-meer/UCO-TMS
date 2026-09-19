<?php

namespace App\Http\Controllers;
use App\Models\Departments;
use Illuminate\Http\Request;

class LayoutservicesController extends Controller
{
    //
    public function dashboard()
    {
         $departments = Departments::all();
    

    return view('admin.creatives.layout.index', compact(
            'departments',
        ));
    }
}
