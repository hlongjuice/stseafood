<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class ProductionEmployee extends Model
{
    protected $table='production_employee';
    protected $fillable=['em_id','group_id'];

    public function employee(){
        return $this->belongsTo('App\Models\Employee','em_id');
    }
    public function productionGroup(){
       return $this->belongsTo('Api\Models\Production\ProductionEmployeeGroup','group');
    }
}
