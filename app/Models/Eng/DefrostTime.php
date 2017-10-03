<?php

namespace App\Models\Eng;

use Illuminate\Database\Eloquent\Model;

class DefrostTime extends Model
{
    protected $table="eng_defrost_time";
    protected $fillable=['time_record','storage_id'];
}
