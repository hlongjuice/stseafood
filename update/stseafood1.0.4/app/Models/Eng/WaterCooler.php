<?php

namespace App\Models\Eng;

use Illuminate\Database\Eloquent\Model;

class WaterCooler extends Model
{
    protected $table="eng_water_cooler";
    protected $fillable=['date','time_record','real_time_record','ripple_temp',
    'ripple_m_13','chilled_tank','pre_tank_temp','pre_tank_mm_8','pre_tank_pump'
    ];
}
