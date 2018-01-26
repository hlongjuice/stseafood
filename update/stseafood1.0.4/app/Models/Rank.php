<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    protected $table ="rank";

    public function employees(){
        return $this->hasMany('App\Models\Employee','rank_id');
    }
}
