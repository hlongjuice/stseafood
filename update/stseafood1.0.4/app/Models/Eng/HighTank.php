<?php

namespace App\Models\Eng;

use Illuminate\Database\Eloquent\Model;

class HighTank extends Model
{
    protected $table="eng_high_tank";
    protected $fillable=['date','time_record','real_time_record','level','pump'];
}
