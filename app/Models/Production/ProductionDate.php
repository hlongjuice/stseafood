<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class ProductionDate extends Model
{
    protected $table='production_date';
    protected $fillable=['date'];

    public function productionDateTime(){
        return $this->hasMany('App\Models\Production\ProductionDateTime','date_id');
    }
}
