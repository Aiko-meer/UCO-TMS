<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Socmed_request extends Model
{
    //
    protected $table = 'socmed_requests';

    protected $fillable = [
        'email',
        'fullname',
        'department',
        'request_id'
        // Add any other columns that belong to the 'socmed_requests' table here
    ];

    public function information()
    {
        return $this->hasOne(Socmed_request_information::class, 'request_id');
    }
}
