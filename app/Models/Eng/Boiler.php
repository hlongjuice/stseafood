<?php

namespace App\Models\Eng;

use Illuminate\Database\Eloquent\Model;

class Boiler extends Model
{
    protected $table="eng_boiler";
    protected $guarded=[];

    public function timeRecords(){
        return $this->hasMany('App\Models\Eng\BoilerTime','eng_boiler_id');
    }
}
