<?php

namespace App\Http\Controllers;
use App\Models\Socmed_request;
use App\Models\Departments;
use App\Models\Socmed_request_information;
use App\Models\Publishing_requests;
use App\Models\Publishing_request_informaitons;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PublishingserviceController extends Controller
{
    //
     public function dashboard()
    {
        return view('admin.Creatives.publishing.index');
    }

        
}
