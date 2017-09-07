<?php

namespace App\Models\QC;

use Illuminate\Database\Eloquent\Model;

class QcShrimpReceiving extends Model
{
    protected $table = "qc_shrimp_receiving";
    protected $fillable = ['created_by_user_id', 'updated_by_user_id',
        'round', 'qc_supplier_receiving_id',
        'shrimp_size', 'shrimp_big', 'shrimp_small', 'shrimp_uf', 'df_shrimp_dead', 'df_shrimp_semi_soft',
        'df_shrimp_soft_shell', 'df_shrimp_scar', 'df_shrimp_bk_line', 'df_shrimp_disabled', 'car_release', 'car_waiting_time',
        'real_shrimp_dead', 'weight'
    ];

    public function waterTemp()
    {
        return $this->hasMany('App\Models\QC\QcWaterTempReceiving', 'qc_shrimp_receiving_id');
    }

    public function supplierReceiving()
    {
        return $this->belongsTo('App\Models\QC\QcSupplierReceiving', 'qc_supplier_receiving_id');
    }
}
