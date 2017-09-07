<?php

namespace App\Models\Eng;

use Illuminate\Database\Eloquent\Model;

class Tank210 extends Model
{
    protected $table="eng_tank_210";
    protected $fillable=['date','time_record','real_time_record','mm_3','level'];
}
