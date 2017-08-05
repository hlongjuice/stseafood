<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Model;

class CarRequestPassenger extends Model
{
    protected $table="car_request_passenger";
    protected $fillable=['car_request_id','em_id'];

    public function carRequest(){
        return $this->belongsTo('App\Models\HumanResource\CarRequest','car_request_id');
    }
}
