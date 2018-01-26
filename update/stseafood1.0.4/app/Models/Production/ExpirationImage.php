<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class ExpirationImage extends Model
{
    protected $table="production_exp_image";
    protected $fillable=[
      'p_exp_id','image_inside','image_outside','image_sticker'
    ];

    public function expiration(){
        return $this->belongsTo('App\Models\Production\ExpirationImage','p_exp_id');
    }
}
