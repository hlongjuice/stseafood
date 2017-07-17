<?php

namespace App\Http\Controllers\Api\Production;

use App\Models\Production\ProductionActivity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivityController extends Controller
{
    public function index(){

    }
    public function getAllActivity(){
        $activities=ProductionActivity::all();
        return response()->json($activities);
    }
    public function getEnableActivity(){
        $activities=ProductionActivity::where('status',1)->get();
        return response()->json($activities);
    }
    public function updateStatus(Request $request,$id){
        $activity=ProductionActivity::where('id',$id)->first();
        $activity->status=$request->input('status');
        $activity->save();
        return response($activity);
    }
    /*Update*/
    public function update(Request $request,$id){
        $activity=ProductionActivity::where('id',$id)->first();
        $activity->name=$request->input('name');
        $activity->save();
        return response($activity);
    }
    /*Delete*/
    public function delete($id){
        $deleteActivity=ProductionActivity::destroy($id);
        return response($deleteActivity);
    }

}
