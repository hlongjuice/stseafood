<?php

namespace App\Http\Controllers\Api\HumanResource;

use App\Models\HumanResource\CarType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarTypeController extends Controller
{
    /*Get Call Type*/
    public function getCarType(){
        $carType=CarType::all();
        return response()->json($carType);
    }
    /*Add Car Type*/
    public function addCarType(Request $request){
        $carType=new CarType();
        $carType->name=$request->input('name');
        $carType->save();
        return response()->json($carType);
    }
    /*Update Car Type*/
    public function updateCarType(Request $request){
        $carType=CarType::where('id',$request->input('id'))->fist();
        $carType->name=$request->input('name');
        $carType->status=$request->input('status');
        $carType->save();
        return response()->json($carType);
    }
    /*Delete Car Type*/
    public function delete(){
        
    }
}
