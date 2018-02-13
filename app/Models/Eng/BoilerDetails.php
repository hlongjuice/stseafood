<?php

namespace App\Models\Eng;

use Illuminate\Database\Eloquent\Model;

class BoilerDetails extends Model
{
    protected $table='eng_boiler_details';
    protected $guarded=[];

    public function timeRecord(){
        return $this->belongsTo('App\Models\Eng\BoilerTime','eng_boiler_time_id');
    }
}
