<?php

namespace App\Models\Eng;

use Illuminate\Database\Eloquent\Model;

class WaterFiltration extends Model
{
    protected $table="eng_water_filtration";
    protected $fillable=['date','time_record','real_time_record','raw_w_pump1','raw_w_pump2','raw_w_pump3',
    'p1_mm1','p2_mm2','chem_pump1','chem_pump2','silt_pump1','silt_pump2'
    ];
}
