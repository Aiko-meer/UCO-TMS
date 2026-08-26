<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublishingserviceController extends Controller
{
    //
     public function dashboard()
    {
        return view('admin.Creatives.publishing.index');
    }
}
