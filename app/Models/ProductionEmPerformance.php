<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionEmPerformance extends Model
{
    protected $table='production_em_performance';

    public function productionDateTime(){
        return $this->belongsTo('App\Models\ProductionDateTime','date_time_id');
    }
    public function employee(){
        return $this->belongsTo('App\Models\Employee','em_id');
    }
}
