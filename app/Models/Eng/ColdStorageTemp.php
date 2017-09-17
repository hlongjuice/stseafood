<?php

namespace App\Models\Eng;

use Illuminate\Database\Eloquent\Model;

class ColdStorageTemp extends Model
{
    protected $table = "eng_cold_storage_temp";
    protected $fillable = ['date', 'time_record', 'real_time_record',
        'cs1_rm', 'cs1_c_1_1', 'cs1_c_1_2', 'cs1_c_1_3',
        'cs1_defrost_status', 'cs2_rm', 'cs2_defrost_status'
    ];
}
