<?php

namespace App\Http\Controllers\Api\HumanResource;

use App\Models\HumanResource\Car;
use App\Models\HumanResource\CarType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class CarController extends Controller
{
    /*Get Cars*/
    public function getCar($type){
        $cars=null;
        if($type=="all"){
            $cars=Car::all();
        }else{
            $cars=Car::where('car_type_id',$type)->get();
        }
        return response()->json($cars);
    }
    /*Get Available Car*/
    public function getAvailableCar($type){
        $car=null;
        if($type=='all'){
            $cars=Car::where('quantity','>',0)->get();
        }else{
            $cars=Car::where('car_type_id',$type)->where('quantity','>',0)
                ->orderBy('quantity','desc')
                ->get();
        }
        return response()->json($cars);
    }
    /*Add New Car*/
    public function addCar(Request $request){
        $this->validate($request,[
            'car_number'=>'required|unique:car'
        ]);
        $result=DB::transaction(function() use($request){
            Car::create([
                'car_number'=>$request->input('car_number'),
                'car_type_id'=>$request->input('car_type_id'),
                'plate_number'=>$request->input('plate_number'),
                'status'=>1
            ]);
            $carType=CarType::where('id',$request->input('car_type_id'))->first();
            $carType->quantity=$carType->quantity+1;
            $carType->save();
        });
        return response()->json($result);
    }
    /*Update Car*/
    public function updateCar(Request $request){
        $this->validate($request,[
            'car_number'=>'required|unique:car'
        ]);
        $car=Car::where('id',$request->input('id'))->first();
        $car->car_number=$request->input('car_number');
        $car->car_type_id=$request->input('car_type_id');
        $car->plate_number=$request->input('plate_number');
        $car->quantity=$request->input('quantity');
        $car->save();
        return response()->json($car);
    }
    /*Update Status*/
    public function updateStatus(Request $request){
        $car=Car::where('id',$request->input('id'))->first();
        $car->status=$request->input('status');
        $car->save();
        return response()->json($car);
    }
    /*Delete Car*/
}
