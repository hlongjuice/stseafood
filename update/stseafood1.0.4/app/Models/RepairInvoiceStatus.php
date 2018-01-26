<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairInvoiceStatus extends Model
{
    protected $table="repair_status";

    public function invoice(){
        return $this->hasMany('App\Models\RepairInvoice','status_id');
    }
}
