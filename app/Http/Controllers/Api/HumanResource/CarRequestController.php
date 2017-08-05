<?php

namespace App\Http\Controllers\Api\HumanResource;

use App\Models\HumanResource\CarRequest;
use App\Models\HumanResource\CarRequestPassenger;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class CarRequestController extends Controller
{
    /*Get Car Request*/
    public function getCarRequest($userID)
    {
        $carRequest=CarRequest::where('requested_by_user_id',$userID)->orderBy('date','desc')->paginate(100);
        return response()->json($carRequest);
    }

    /*Add Car Request*/
    public function addCarRequest(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $passenger = [];
            $addPassenger = new CarRequestPassenger();
            $carRequest = CarRequest::create([
                'date' => $request->input('date'),
                'car_type_id' => $request->input('car_type_id'),
                'division_id' => $request->input('division_id'),
                'em_id' => $request->input('em_id'),
                'rank_id' => $request->input('rank_id'),
                'destination' => $request->input('destination'),
                'details' => $request->input('details'),
                'requested_by_user_id' => $request->input('requested_by_user_id'),
                'status_id'=>1// Waiting For Approve
            ]);
            if ($request->has('passenger')) {
                foreach ($request->input('passenger') as $emID) {
                    $passenger[] = [
                        'car_request_id' => $carRequest->id,
                        'em_id' => $emID
                    ];
                }
                $addPassenger->insert($passenger);
            }
        });
        return response()->json($result);
    }
    /*Update Car Request*/
    public function updateCarRequest(Request $request){
        $carRequest=CarRequest::where('id',$request->input('id'))->update([
            'date' => $request->input('date'),
            'car_type_id' => $request->input('car_type_id'),
            'division_id' => $request->input('division_id'),
            'em_id' => $request->input('em_id'),
            'rank_id' => $request->input('rank_id'),
            'destination' => $request->input('destination'),
            'details' => $request->input('details'),
            'updated_by_user_id' => $request->input('updated_by_user_id')
        ]);
        if($request->has('passenger')){
            /*Delete All Passenger*/
            $passengers=[];
            $addPassenger=CarRequestPassenger::where('car_request_id',$request->input('id'))->delete();
            /*And Add All Passenger*/
            foreach ($request->input('passenger') as $emID) {
                $passengers[] = [
                    'car_request_id' => $carRequest->id,
                    'em_id' => $emID
                ];
            }
            $addPassenger->insert($passengers);
        }
    }

}
