<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class ProductionEmployee extends Model
{
    protected $table='production_employee';

    public function employee(){
        return $this->belongsTo('App\Models\Employee','em_id');
    }
}
