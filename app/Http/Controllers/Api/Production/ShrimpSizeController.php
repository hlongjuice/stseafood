<?php

namespace App\Http\Controllers\Api\Production;

use App\Models\Production\ProductionShrimpSize;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShrimpSizeController extends Controller
{
    public function getAllSize(){
        $size=ProductionShrimpSize::all();
        return response()->json($size);
    }
    public function getEnableSize(){
        $size=ProductionShrimpSize::where('status',1)->orderBy('name')->get();
        return response()->json($size);
    }
    /*Update*/
    public function update(Request $request,$id){
        $size=ProductionShrimpSize::where('id',$id)->first();
        $size->name=$request->input('name');
        $size->save();
    }
    /*Update Status*/
    public function updateStatus(Request $request,$id){
        $size=ProductionShrimpSize::where('id',$id)->first();
        $size->status=$request->input('status');
        $size->save();
    }
    /*Delete*/
    public function delete($id){
        $size=ProductionShrimpSize::destroy($id);
        return response($size);
    }
}
