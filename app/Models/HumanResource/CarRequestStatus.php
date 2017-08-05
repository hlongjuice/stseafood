<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Model;

class CarRequestStatus extends Model
{
    protected $table='car_request_status';

    public function carRequest(){
        return $this->hasMany('App\Models\HumanResource\CarRequestStatus','status_id');
    }
}
