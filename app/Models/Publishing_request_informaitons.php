<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publishing_request_informaitons extends Model
{
    //
     protected $fillable = [
        'request_id',
        'purpose',
        'date_needed',
        'category',
        'other_category',
        'specification',
        'content_information',
        'content_attachement',
        'section',
        'approve',
        'produce',
        'published',
        'status',
        'reference'
        // Add any other columns that belong to the 'socmed_requests' table here
    ];

    public function request()
    {
        return $this->belongsTo(Publishing_requests::class, 'request_id','request_id');
    }
}
