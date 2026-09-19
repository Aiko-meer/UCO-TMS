<?php

namespace App\Http\Controllers;
use App\Models\Departments;
use App\Models\Photo_vid_requests;
use App\Models\Photo_vid_request_informations;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    //
    public function dashboard()
    {
        // Fetches parent records along with their corresponding child information
       $requests = Photo_vid_requests::with('information')
       ->whereHas('information', 
       function ($query) { $query->where('category', 'vid'); })->orderBy('id', 'desc')->get();
        $departments = Departments::all();
         $actviepagi  = Photo_vid_requests::with('information')->paginate(5, ['fullname'], 'active_page');
    $archivepagi =Photo_vid_requests::with('information')->paginate(10);
    $monthpagi =Photo_vid_requests::with('information')->paginate(10);
    $listpagi =Photo_vid_requests::with('information')->paginate(5);

     $month = request('month', date('m')); // Defaults to current month if not selected
     $year = request('year', date('Y')); // Defaults to current year if not selected
        $archived = Photo_vid_requests::with('information')
        ->whereHas('information', function ($query) use ($year, $month) {
            $query->where('status', 2)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month);
        })
        ->get();
        return view('admin.video.index', compact(
            'departments',
            'requests',
            'archived',
            'actviepagi',
            'archivepagi',
            'monthpagi',
            'listpagi'
        ));
    }
}
