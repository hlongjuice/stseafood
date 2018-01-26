<?php

namespace App\Models\Eng;

use Illuminate\Database\Eloquent\Model;

class WaterMeter extends Model
{
    protected $table="eng_water_meter";
    protected $fillable=['date','time_record','real_time_record','mm_4','mm_5','mm_6'];
}
