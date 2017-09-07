<?php

namespace App\Models\Eng;

use Illuminate\Database\Eloquent\Model;

class WaterSupplyOutSide extends Model
{
    protected $table="eng_water_supply_outside";
    protected $fillable=['date','real_time_record','time_record','m_pwa','m_15','m_17',
    'm_18','m_19','m_20','m_21'
    ];
    
}
