<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionDateTime extends Model
{
    protected $table='production_date_time';

    public function productionDate(){
        return  $this->belongsTo('App\ProductionDate','date_id');
    }
    public function productionEmPerformance(){
        return $this->hasMany('App\Models\ProductionEmPerformance','date_time_id');
    }
}
