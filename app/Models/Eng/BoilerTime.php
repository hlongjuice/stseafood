<?php

namespace App\Models\Eng;

use Illuminate\Database\Eloquent\Model;

class BoilerTime extends Model
{
    protected $table='eng_boiler_time';
    protected $guarded=[];

    public function allDetails(){
        return $this->hasMany('App\Models\Eng\BoilerDetails','eng_boiler_time_id');
    }
}
