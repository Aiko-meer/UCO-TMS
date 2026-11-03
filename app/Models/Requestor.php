<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requestor extends Model
{
    //
    protected $fillable = [
        'email',
        'f_name',
        'l_name',
        'middle',
        'department',
        'request_id',
        'token'
        // Add any other columns that belong to the 'socmed_requests' table here
    ];

    public function socmed()
    {
        return $this->hasOne(Socmed_request_information::class, 'request_id','request_id');
    }

    public function photo()
    {
        return $this->hasOne(Photo_vid_request_informations::class, 'request_id','request_id');
    }

    public function publishing()
    {
        return $this->hasOne(Publishing_request_informaitons::class, 'request_id','request_id');
    }
}
