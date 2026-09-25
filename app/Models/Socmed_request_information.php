<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Socmed_request_information extends Model
{
    //
    protected $table = 'socmed_request_informations';

     protected $fillable = [
        'request_id',
        'purpose',
        'date_needed',
        'category',
        'specification',
        'content_information',
        'section',
        'approve',
        'produce',
        'published',
        'status',
        'content_attachement'
        // Add any other columns that belong to the 'socmed_requests' table here
    ];

    public function request()
    {
        return $this->belongsTo(Socmed_request::class, 'request_id', 'request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'produce', 'employee_id');
    }
}
