<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table="production_product";
    protected $fillable=[
      'name','date_format','exp_day'
    ];

    public function expiration(){
        return $this->hasMany('App\Models\Production\Expiration','product_id');
    }
}
