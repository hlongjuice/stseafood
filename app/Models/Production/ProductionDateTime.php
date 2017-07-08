<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class ProductionDateTime extends Model
{
    protected $table='production_date_time';

    public function productionDate(){
        return  $this->belongsTo('App\Models\Production\ProductionDate','date_id');
    }

    public function productionWork(){
        return $this->hasMany('App\Models\Production\ProductionWork','p_date_time_id');
    }
}
