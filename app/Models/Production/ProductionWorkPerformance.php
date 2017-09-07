<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class ProductionWorkPerformance extends Model
{
    protected $table='production_work_performance';
    protected $fillable=['em_id','p_work_id','weight','created_by_user_id','updated_by_user_id'];

    public function employee(){
        return $this->belongsTo('App\Models\Employee','em_id');
    }
    /*Production Work*/
    public function productionWork(){
        return $this->belongsTo('App\Models\Production\ProductionWork','p_work_id');
    }
}
