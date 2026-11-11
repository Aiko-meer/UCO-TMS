<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrademarkRequest extends Model
{
    //
    Protected $fillable = [
          'request_id',
          'salutation',
            'f_name',
            'middle_name',
            'l_name',
            'category_requestor',
            'position',
            'department_org',
            'contact_number',
            'email',
            'office_address',
            'trademark_to_use',
            'date_to_use',
            'category_trademark_use',
            'target_audience',
            'materials_media',
            'location_use',
            'purpose_of_use',
            'note',
            'approved_by',
            'status',
    ];
   
}
