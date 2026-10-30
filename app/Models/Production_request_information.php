<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production_request_information extends Model
{

    protected $table = 'Production_request_information';
    //
     protected $fillable = [
        'request_id',
        'size',
        'quantity',
        'type_material'
        // Add any other columns that belong to the 'socmed_requests' table here
    ];

      public function information()
    {
        return $this->belongsTo(Publishing_requests::class, 'request_id','request_id');
    }
}
