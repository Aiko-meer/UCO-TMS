<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo_vid_request_informations extends Model
{
    //
    protected $fillable = [
        'request_id',
        'event',
        'date_needed',
        'category',
        'section',
        'approve',
        'produce',
        'published',
        'status',
        'venue',
        'start_time',
        'end_time',
        'purpose_document',
        'equipment',

        // Add any other columns that belong to the 'socmed_requests' table here
    ];

     public function request()
    {
        return $this->belongsTo(Photo_vid_requests::class, 'request_id');
    }
}
