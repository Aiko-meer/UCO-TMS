<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CreativesuserController extends Controller
{
    //
    public function dashboard()
    {
        return view('admin.account.user.creatives.index');
    }

    
}
