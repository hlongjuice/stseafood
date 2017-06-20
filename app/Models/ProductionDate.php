<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionDate extends Model
{
    protected $table='production_date';
    protected $fillable=['date'];

    public function productionDateTime(){
        return $this->hasMany('App\Models\ProductionDateTime','date_id');
    }
}
