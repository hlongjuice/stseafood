<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $table="car";
    protected $fillable=['id','car_number','car_type_id','plate_number','status','quantity'];

    public function carType(){
        return $this->belongsTo('App\Models\HumanResource\CarType','car_type_id');
    }
}
