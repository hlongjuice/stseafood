<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Model;

class CarUsage extends Model
{
    protected $table='car_usage';
    protected $fillable=['car_id','date_departure','time_departure','date_arrival','time_arrival'
        ,'response_id','mile_start','mile_end','recorded_by_user_id','gas_fill','gas_station'
        ,'gas_unit_price','gas_total_price','distance','distance_per_litre','price_per_distance','car_access_status_id'
    ];

    /*Car*/
    public function car(){
        return $this->belongsTo('App\Models\HumanResource\Car','car_id');
    }
    /*Car Response*/
    public function carResponse(){
        return $this->belongsTo('App\Models\HumanResource\CarResponse','response_id');
    }
    /*Recorder*/
    public function recorder(){
        return $this->belongsTo('App\Models\Employee','recorded_by_user_id');
    }
}
