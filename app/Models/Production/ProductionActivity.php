<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionActivity extends Model
{
    protected $table='production_activity';

    public function productionWork(){
        return $this->hasMany('App\Models\Production\ProductionWork','p_activity_id');
    }
}
