<?php

namespace App\Http\Controllers;
use App\Models\Departments;
use Illuminate\Http\Request;

class UzpostController extends Controller
{
    //
    public function dashboard()
    {   
        $departments = Departments::all();
        return view('admin.Creatives.uzpost.index', compact(
            'departments'
            ));
    }
}
