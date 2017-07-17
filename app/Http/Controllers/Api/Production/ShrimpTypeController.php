<?php

namespace App\Http\Controllers\Api\Production;

use App\Models\Production\ProductionShrimpType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShrimpTypeController extends Controller
{
    public function getAllType(){
        $types=ProductionShrimpType::all();
        return response()->json($types);
    }
    public function getEnableType(){
        $types=ProductionShrimpType::where('status',1)->orderBy('name')->get();
        return response()->json($types);
    }

    /*Update*/
    public function update(Request $request,$id){
        $type=ProductionShrimpType::where('id',$id)->first();
        $type->name=$request->input('name');
        $type->save();
        return response($type);
    }
    /*Update Status*/
    public function updateStatus(Request $request,$id){
        $type=ProductionShrimpType::where('id',$id)->first();
        $type->status=$request->input('status');
        $type->save();
        return response($type);
    }
    /*Delete*/
    public function delete($id){
        $type=ProductionShrimpType::destroy($id);
        return response($type);
    }
    public function add(Request $request){
        $type=new ProductionShrimpType();
        $type->name=$request->input('name');
        $type->status=1;
        $type->save();
        return response($type);
    }
}
