<?php

namespace App\Models\Eng;

use Illuminate\Database\Eloquent\Model;

class IceMaker extends Model
{
    protected $table="eng_ice_maker";
    protected $fillable=['date','time_record','real_time_record',
    'freezer1_m_12','freezer2_m_2','freezer3_m_14','recei_no1'
    ];
}
