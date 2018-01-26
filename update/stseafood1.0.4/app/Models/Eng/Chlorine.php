<?php

namespace App\Models\Eng;

use Illuminate\Database\Eloquent\Model;

class Chlorine extends Model
{
    protected $table="eng_chlorine";
    protected $fillable=['date_time_id','real_time_record',
    'level','stroke','stroke_l','lab'
    ];

    public function dateTime(){
        return $this->belongsTo('App\Models\Eng\EngDateTime','date_time_id');
    }
}
