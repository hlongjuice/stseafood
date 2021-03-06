<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairInvoice extends Model
{
    protected $table="repair_invoice";
    protected $fillable=[
        'date','time','status_id','approver_id','division_id','sender_id',
        'item','item_details','image','repair_receiver_id'
    ];
    //status
    public function status(){
        return $this->belongsTo('App\Models\RepairInvoiceStatus','status_id');
    }
    //Division
    public function division(){
        return $this->belongsTo('App\Models\Division','division_id');
    }
    //sender
    public function sender(){
        return $this->belongsTo('App\User','sender_id');
    }
    //Approver
    public function approver(){
        return $this->belongsTo('App\User','approver_id');
    }

    //Receiver
    public function receiver(){
        return $this->belongsTo('App\Models\RepairReceiver','repair_receiver_id');
    }
}
