<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Model;

class CarType extends Model
{
    protected $table ="car_type";
    /*Car*/
    public function car(){
        return $this->hasMany('App\Models\HumanResource\Car','car_type_id');
    }
    /*Car Request*/
    public function carRequest(){
        return $this->hasMany('App\Models\HumanResource\CarRequest','car_type_id');
    }

}
