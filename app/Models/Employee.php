<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table='employee';
    protected $primaryKey='em_id';
    protected $fillable=['em_id','name','lastname','division_id','salary_type_id'];

    public function productionEmPerformance(){
        return $this->hasMany('App\Models\Production\ProductionWorkPerformance','em_id');
    }
    /*Division*/
    public function division(){
        return $this->belongsTo('App\Models\Division','division_id');
    }
    /*Department*/
    public function department(){
        return $this->belongsTo('App\Models\Department','department_id');
    }
    /*Production Employee*/
    public function productionEmployee(){
        return $this->hasMany('App\Models\Production\ProductionEmployee','em_id');
    }
    public function salaryType(){
        return $this->belongsTo('App\Models\SalaryType','salary_type_id');
    }
    
    public function rank(){
        return $this->belongsTo('App\Models\Rank','rank_id');
    }
}
