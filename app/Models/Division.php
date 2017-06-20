<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $table='divisions';

    public function employees(){
        return $this->hasMany('App\Employee','division_id');
    }
}
