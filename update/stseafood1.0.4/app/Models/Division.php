<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $table='divisions';

    public function employees(){
        return $this->hasMany('App\Models\Employee','division_id');
    }
    /*Car Request*/
    public function carRequest(){
        return $this->hasMany('App\Models\HumanResource\CarRequest','division_id');
    }
}
