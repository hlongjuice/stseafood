<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class ProductionWorkPerformance extends Model
{
    protected $table='production_work_performance';

    public function employee(){
        return $this->belongsTo('App\Models\Employee','em_id');
    }
    /*Production Work*/
    public function productionWork(){
        return $this->belongsTo('App\Models\Production\ProductionWork','p_work_id');
    }
}
