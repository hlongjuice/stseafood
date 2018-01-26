<?php

namespace App\Http\Controllers\Api\Production;

use App\Models\Production\ProductionShrimpSize;
use App\Models\Production\ProductionShrimpType;
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
        return response($size);
    }
    /*Update Status*/
    public function updateStatus(Request $request,$id){
        $size=ProductionShrimpSize::where('id',$id)->first();
        $size->status=$request->input('status');
        $size->save();
        return response($size);
    }
    /*Delete*/
    public function delete($id){
        $size=ProductionShrimpSize::destroy($id);
        return response($size);
    }
    /*Add*/
    public function add(Request $request){
        $size=new ProductionShrimpSize();
        $size->name=$request->input('name');
        $size->status=1;
        $size->save();
        return response($size);
    }
}
