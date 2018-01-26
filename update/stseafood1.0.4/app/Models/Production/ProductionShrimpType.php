<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class ProductionShrimpType extends Model
{
    protected $table='production_shrimp_type';
    public function productionWork(){
        return $this->hasMany('App\Models\Production\ProductionWork','p_shrimp_type_id');
    }
}
