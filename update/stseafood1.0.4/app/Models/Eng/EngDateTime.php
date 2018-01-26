<?php

namespace App\Models\Eng;

use Illuminate\Database\Eloquent\Model;

class EngDateTime extends Model
{
    protected $table="eng_date_time";
    protected $fillable=['date','time_record'];

    public function chlorine(){
        return $this->hasOne('App\Models\Eng\Chlorine','date_time_id');
    }
    public function lab(){
        return $this->hasOne('App\Models\Eng\ChlorineLab','date_time_id');
    }
}
