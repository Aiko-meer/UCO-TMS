<?php

namespace App\Http\Controllers;
use App\Models\Requestor;
use App\Models\Soft_copy_requests;
use App\Models\TrademarkRequest;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
     public function dashboard()
    {
         $socmed = Requestor::where('token', '0')->has('socmed')->get();
        $photovid = Requestor::where('token', '0')->has('photo')->get();
        $softcopy = Soft_copy_requests::where('token', '0')->get();
        $trade = TrademarkRequest::where('token', '0')->get();
        $layout = Requestor::where('token', '0')
        ->whereHas('publishing', function ($query) {
            $query->where('specification', 'layout');
        })
        ->get();
         $production = Requestor::where('token', '0')
        ->whereHas('publishing', function ($query) {
            $query->where('specification', 'production');
        })
        ->get();
         $uzpr = Requestor::where('token', '0')
        ->whereHas('publishing', function ($query) {
            $query->where('specification', 'uzpr');
        })
        ->get();
         $uzpost = Requestor::where('token', '0')
        ->whereHas('publishing', function ($query) {
            $query->where('specification', 'uzpost');
        })
        ->get();

        $totalNotifications = $socmed->count() + $photovid->count() + $layout->count() + $production->count() + $uzpr->count() + $uzpost->count() + $softcopy->count() + $trade->count();
        return view('admin.index',compact(
            'socmed',
            'photovid',
            'layout',
            'production',
            'uzpost',
            'uzpr',
            'softcopy',
            'trade',
            'totalNotifications'
        ));
    }
}
