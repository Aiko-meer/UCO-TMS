<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContentuserController extends Controller
{
    //
     public function usertable()
    {
        return view('admin.account.user.content.index');
    }


     public function socmed()
    {
        return view('users.content.table.socmed.index');
    }

    public function socmedtable()
    {
        return view('users.content.table.socmed.table.table');
    }
}
