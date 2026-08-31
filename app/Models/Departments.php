<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    //
    protected $fillable = [
        'code',
        'name'
        // Add any other columns that belong to the 'socmed_requests' table here
    ];
}
