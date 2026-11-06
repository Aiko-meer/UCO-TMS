<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soft_copy_requests extends Model
{
    //
    protected $fillable = [
        'f_name',
        'l_name',
        'middle_name',
        'request_id',
        'email',
        'year_level',
        'grad',
        'school_level',
        'program',
        'number',
        'date_photo',
        'id_attachment',
        'id_number',
        'is_graduating',
        'status',
        // Add any other columns that belong to the 'socmed_requests' table here
    ];
}
