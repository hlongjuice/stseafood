<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    protected $table="user_details";
    protected $fillable=[
        'user_id','car_assign','car_approve','repair_approve'
    ];

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
}
