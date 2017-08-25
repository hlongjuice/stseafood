<?php

namespace App\Models\QC;

use Illuminate\Database\Eloquent\Model;

class QcSupplierReceiving extends Model
{
    protected $table="qc_supplier_receiving";
    protected $fillable=['supplier_id','pond','code','date'];
    
    public function shrimpReceiving(){
        return $this->hasMany('App\Models\QC\QcShrimpReceiving','qc_supplier_receiving_id');
    }
    //Suppliers
    public function supplier(){
        return $this->belongsTo('App\Models\Supplier','supplier_id');
    }
}
