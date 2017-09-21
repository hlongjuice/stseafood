<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'lastname','email', 'password','username','car_approve','car_assign',
        'repair_approve','division_id'
    ];

    /*If use custom Auth use this method to bind it*/
    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
//    public function details(){
//        return $this->hasOne('App\Models\UserDetails','user_id');
//    }

    //Division
    public function division(){
        return $this->belongsTo('App\Models\Division','division_id');
    }
    //Type
    public function type(){
        return $this->belongsTo('App\Models\UserType','type_id');
    }
}
