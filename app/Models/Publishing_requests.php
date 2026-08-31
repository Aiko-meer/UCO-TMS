<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publishing_requests extends Model
{
    //
     protected $fillable = [
        'email',
        'fullname',
        'department',
        'request_id'
        // Add any other columns that belong to the 'socmed_requests' table here
    ];

    public function information()
    {
        return $this->hasOne(Publishing_request_informaitons::class, 'request_id');
    }
}
