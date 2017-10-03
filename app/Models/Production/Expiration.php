<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class Expiration extends Model
{
    protected $table='production_exp';
    protected $fillable=[
        'product_id','mfd','code','exp_date','date_format'
    ];

    public function build(){
        return $this->hasMany('App\Models\Production\ExpirationBuild','p_exp_id');
    }
    //Product
    public function product(){
        return $this->belongsTo('App\Models\Production\Product','product_id');
    }
    //Image
    public function image(){
        return $this->hasOne('App\Models\Production\ExpirationImage','p_exp_id');
    }
}
