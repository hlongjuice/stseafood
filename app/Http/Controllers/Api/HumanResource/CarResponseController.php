<?php

namespace App\Http\Controllers\Api\HumanResource;

use App\Models\HumanResource\Car;
use App\Models\HumanResource\CarRequest;
use App\Models\HumanResource\CarResponse;
use App\Models\HumanResource\CarType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class CarResponseController extends Controller
{
    /*Get Car Request*/
    public function getCarRequest($status)
    {
        $carRequest = null;
        if ($status == 'ทั้งหมด') {
            $carRequest = CarRequest::orderBy('date', 'desc')->paginate(100);
        } else {
            $carRequest = CarRequest::where('status_id', $status)->orderBy('date', 'desc')->paginate(100);
        }
    }

    /*Get Response*/
    public function getCarResponse($userID){
        $carResponse=CarResponse::where('updated_by_user_id',$userID)->orderBy('date','desc')->paginate(100);
        return response()->json($carResponse);
    }

    /*Approve Request*/
    public function approveRequest(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $carRequestList = [];
            $quantity=0;
            $carResponse = CarResponse::create([
                'date' => $request->input('date'),
                'car_id' => $request->input('car_id'),
                'driver_id' => $request->input('driver_id'),
                'destination' => $request->input('destination'),
                'details' => $request->input('details'),
                'approved_by_user_id' => $request->input('user_id'),
                'updated_by_user_id'=>$request->input('user_id')
            ]);
            foreach ($request->input('car_request_id') as $requestID) {
                $carRequestList[] = [
                    'car_request_id' => $requestID
                ];
            }
            $carResponse->carRequest()->attach($carRequestList);
            /*Update Request Status*/
            $carRequest = CarRequest::whereIn('id', $request->input('car_request_id'))->update(
                ['status_id' => 2]//Approve Status
            );

            /*Decrease Quantity */
            $carType=CarType::where('id',$request->inpit('car_type_id'))->first();
            $carType->quantity=$carType->quantity-1;
            $carType->save();
        });
        return response()->json($result);
    }

    /*Edit Response*/
    public function updateResponse(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $carResponse = CarResponse::with('carRequest')->where('id', $request->input('id'))
                ->update([
                    'date' => $request->input('date'),
                    'car_id' => $request->input('car_id'),
                    'driver_id' => $request->input('driver_id'),
                    'destination' => $request->input('destination'),
                    'details' => $request->input('details'),
                    'approved_by_user_id' => $request->input('user_id')
                ]);
        });
        return response()->json($result);
    }
    /*Delete Response*/
    public function deleteResponse(Request $request){
        $result=DB::transaction(function() use($request){
            DB::table('car_response_request')->whereIn('id',$request->input('response_id'))->delete();
            CarResponse::whereIn('id',$request->input('response_id'))->delete();
        });
        return response()->json($result);
    }
    /*Delete Request*/
    public function deleteResponseRequest(Request $request){
        $carRequestIDList=[];
        foreach ($request->input('car_request_ids') as $carRequestID){
            $carRequestIDList[]=[
                'car_request_id'=>$carRequestID
            ];
        }
        $carResponse=CarResponse::with('carRequest')->where('id',$request->input('response_id'))->first();
        $carResponse->carRequest()->detach($carRequestIDList);
        return response()->json($carResponse);
    }
}
