<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo_vid_requests extends Model
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
        return $this->hasOne(Photo_vid_request_informations::class, 'request_id','request_id');
    }
}
