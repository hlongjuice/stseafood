<?php

namespace App\Models\Eng;

use Illuminate\Database\Eloquent\Model;

class Boiler extends Model
{
    protected $table="eng_boiler";
    protected $fillable=['date','time_record','real_time_record',
        'boiler1','boiler1_meter','boiler1_tank_l',
        'boiler2','boiler2_meter','boiler2_tank_l'
    ];
}
