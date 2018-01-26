<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class ExpirationBuild extends Model
{
    protected $table="production_exp_build";
    protected $fillable=[
        'p_exp_id','q_box','q_inside','q_outside','q_sticker','round'
    ];

    public function expiration(){
        return $this->belongsTo('App\Models\Production\Expiration','p_exp_id');
    }
}
