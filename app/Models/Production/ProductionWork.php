<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class ProductionWork extends Model
{
    protected $table = "production_work";

    public function productionDateTime()
    {
        return $this->belongsTo('App\Models\Production\ProductionDateTime', 'p_date_time');
    }

    public function productionWorkPerformance()
    {
        return $this->hasMany('App\Models\Production\ProductionWorkPerformance', 'p_work_id');
    }

    /*Activity*/
    public function productionActivity()
    {
        return $this->belongsTo('App\Models\Production\ProductionActivity', 'p_activity_id');
    }

    /*Shrimp Type*/
    public function productionShrimpType()
    {
        return $this->belongsTo('App\Models\Production\ProductionShrimpType', 'p_shrimp_type');
    }

    /*Shrimp Size*/
    public function productionShrimpSize()
    {
        return $this->belongsTo('App\Models\Production\ProductionShrimpSize','p_shrimp_size');
    }
}
