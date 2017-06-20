<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table='employee';

    public function productionEmPerformance(){
        return $this->hasMany('App\Models\ProductionEmPerformance','em_id');
    }
    public function division(){
        return $this->belongsTo('App\Models\Division','division_id');
    }
}
