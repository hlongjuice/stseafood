<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Model;

class CarRequest extends Model
{
    protected $table ='car_request';
    protected $fillable=[
        'start_date','end_date','start_time','end_time','car_type_id','division_id',
        'em_id','rank_id','destination','status_id','passenger_number',
        'details','requested_by_user_id','updated_by_user_id'
    ];
    public $timestamps = false;
    public function carResponse(){
        return $this->belongsToMany('App\Models\HumanResource\CarResponse','car_request_response','car_request_id','car_response_id')
            ->withTimestamps();
    }

    public function carType(){
        return $this->belongsTo('App\Models\HumanResource\CarType','car_type_id');
    }
    /*Passenger*/
    public function passenger(){
        return $this->hasMany('App\Models\HumanResource\CarRequestPassenger','car_request_id');
    }
    /*Car Request Status*/
    public function status(){
        return $this->belongsTo('App\Models\HumanResource\CarRequestStatus','status_id');
    }

    /*Divisions*/
    public function division(){
        return $this->belongsTo('App\Models\Division','division_id');
    }
    /*Employee*/
    public function employee(){
        return $this->belongsTo('App\Models\Employee','em_id');
    }
    /*Rank*/
    public function rank(){
        return $this->belongsTo('App\Models\Rank','rank_id');
    }

}
