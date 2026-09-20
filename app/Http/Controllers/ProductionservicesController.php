<?php

namespace App\Http\Controllers;
use App\Models\Departments;
use Illuminate\Http\Request;

class ProductionservicesController extends Controller
{
    //
     public function dashboard()
    {
         $departments = Departments::all();
        return view('admin.creatives.production.index', compact(
            'departments',
           
        ));
    }
}
