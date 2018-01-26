<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryType extends Model
{
    protected $table='employee_salary_type';

    public function employee(){
        return $this->hasMany('Api\Models\Employee','salary_type_id');
    }
}
