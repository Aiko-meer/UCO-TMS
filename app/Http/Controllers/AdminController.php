<?php

namespace App\Http\Controllers;
use App\Models\Socmed_request;
use App\Models\Photo_vid_requests;
use App\Models\Publishing_requests;
use App\Models\Publishing_request_informaitons;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
     public function dashboard()
    {
        $socmed = Socmed_request::where('token', '0')->get();
        $photovid = Photo_vid_requests::where('token', '0')->get();
        $layout = Publishing_requests::where('token', '0')
        ->whereHas('information', function ($query) {
            $query->where('specification', 'layout');
        })
        ->get();
         $production = Publishing_requests::where('token', '0')
        ->whereHas('information', function ($query) {
            $query->where('specification', 'production');
        })
        ->get();
         $uzpr = Publishing_requests::where('token', '0')
        ->whereHas('information', function ($query) {
            $query->where('specification', 'uzpr');
        })
        ->get();
         $uzpost = Publishing_requests::where('token', '0')
        ->whereHas('information', function ($query) {
            $query->where('specification', 'uzpost');
        })
        ->get();

        $totalNotifications = $socmed->count() + $photovid->count() + $layout->count() + $production->count() + $uzpr->count() + $uzpost->count();
        return view('admin.index',compact(
            'socmed',
            'photovid',
            'layout',
            'production',
            'uzpost',
            'uzpr',
            'totalNotifications'
        ));
    }
}
