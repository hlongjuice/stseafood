<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $table="car_driver";

    public function employee(){
        return $this->belongsTo('App\Models\Employee','em_id');
    }
}
