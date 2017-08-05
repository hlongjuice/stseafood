<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Model;

class CarResponse extends Model
{
    protected $table="car_response";
    protected $fillable=['date','car_id','driver_id',
        'destination','details','approved_by_user_id'];

    /*Car*/
    public function car(){
        return $this->belongsTo('App\Models\HumanResource\Car','car_id');
    }
    /*Car Request*/
    public function carRequest(){
        return $this->belongsToMany('App\Models\HumanResource\CarRequest','car_request_response','car_request_id','car_response_id');
    }

    /*Driver*/
    public function driver(){
        return $this->belongsTo('App\Models\Employee','driver_id');
    }
}
