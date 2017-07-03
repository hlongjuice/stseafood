<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class ProductionShrimpSize extends Model
{
    protected $table='production_shrimp_size';
    public function productionWork(){
        return $this->hasMany('App\Models\Production\ProductionWork','p_shrimp_size_id');
    }
}
