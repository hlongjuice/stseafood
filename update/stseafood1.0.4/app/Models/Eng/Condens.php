<?php

namespace App\Models\Eng;

use Illuminate\Database\Eloquent\Model;

class Condens extends Model
{
    protected $table="eng_condens";
    protected $fillable=['date','time_record','real_time_record',
        'con2_w_meter','con3_w_meter','con5_meter_m5','con6_meter_m6',
        'con7_meter_m7','con8_w_meter'
    ];
}
