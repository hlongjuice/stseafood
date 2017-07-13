<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class ProductionEmployeeGroup extends Model
{
    protected $table="production_employee_group";

    public function employee(){
        return $this->hasMany('Api\Models\Production\ProductionEmployee','group');
    }
}
