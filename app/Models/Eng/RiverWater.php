<?php

namespace App\Models\Eng;

use Illuminate\Database\Eloquent\Model;

class RiverWater extends Model
{
    protected $table="eng_river_water";
    protected $fillable=['date','time_record','real_time_record',
        'bar','level'
    ];
}
