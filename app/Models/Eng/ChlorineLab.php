<?php

namespace App\Models\Eng;

use Illuminate\Database\Eloquent\Model;

class ChlorineLab extends Model
{
    protected $table="eng_chlorine_lab";
    protected $fillable=['date_time_id','real_time_record',
    'chlorine','p1','p2'
    ];
    
    public function dateTime(){
        return $this->belongsTo('App\Models\Eng\EngDateTime','date_time_id');
    }
}

