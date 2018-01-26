<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Model;

class CarResponse extends Model
{
    protected $table = "car_response";
    protected $fillable = ['date', 'time', 'car_id', 'driver_id', 'status_id',
        'assigned_by_user_id', 'destination', 'details', 'approved_by_user_id'];

    /*Car*/
    public function car()
    {
        return $this->belongsTo('App\Models\HumanResource\Car', 'car_id');
    }

    /*Car Usage*/
    public function carUsage()
    {
        return $this->hasOne('App\Models\HumanResource\CarUsage', 'response_id');
    }

    /*Car Request*/
    public function carRequest()
    {
        return $this->belongsToMany('App\Models\HumanResource\CarRequest', 'car_request_response', 'car_response_id', 'car_request_id')
            ->withTimestamps();
    }

    /*Driver*/
    public function driver()
    {
        return $this->belongsTo('App\Models\Employee', 'driver_id');
    }

    /*Employee*/
    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'em_id');
    }

    /*Division*/
    public function division()
    {
        return $this->belongsTo('App\Models\Division', 'division_id');
    }

    /*status*/
    public function status()
    {
        return $this->belongsTo('App\Models\HumanResource\CarRequestStatus', 'status_id');
    }

    /*Assigner*/
    public function assigner()
    {
        return $this->belongsTo('App\User', 'assigned_by_user_id');
    }

    /*Car Approver*/
    public function approver()
    {
        return $this->belongsTo('App\User', 'approved_by_user_id');
    }
}
