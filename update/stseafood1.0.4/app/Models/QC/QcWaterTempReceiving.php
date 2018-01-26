<?php

namespace App\Models\QC;

use Illuminate\Database\Eloquent\Model;

class QcWaterTempReceiving extends Model
{
    protected $table="qc_water_temp_receiving";
    protected $fillable=['qc_shrimp_receiving_id','value'];

    public function shrimpReceiving(){
        return $this->belongsTo('App\Models\QC\QcShrimpReceiving','qc_shrimp_receiving_id');
    }
}
