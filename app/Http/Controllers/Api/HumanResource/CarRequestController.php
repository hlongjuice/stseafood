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
    public function getCarRequest(Request $request)
    {
        $carRequest = CarRequest::with('carType', 'status', 'division', 'employee', 'rank', 'passenger.employee')
            ->where('requested_by_user_id', $request->input('user_id'))
            ->whereDate('start_date',$request->input('date'))
            ->orWhere('updated_at',$request->input('date'))
            ->orderBy('start_date', 'desc')->orderBy('start_time', 'desc')->paginate(100);
        return response()->json($carRequest);
    }
    //Get Car Request By Month
    public function getCarRequestByMonth(Request $request){
        $carRequest = CarRequest::with('carType', 'status', 'division', 'employee',
            'rank', 'passenger.employee','carResponse')
            ->where('requested_by_user_id', $request->input('user_id'))
            ->whereMonth('start_date',$request->input('month'))
            ->whereYear('start_date',$request->input('year'))
            ->orderBy('start_date', 'desc')->orderBy('start_time', 'desc')->paginate(100);
        return response()->json($carRequest);
    }

    /*Add Car Request*/
    public function addCarRequest(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $passenger = [];
            $addPassenger = new CarRequestPassenger();
            $carRequest = CarRequest::create([
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'start_time' => $request->input('start_time'),
                'end_time' => $request->input('end_time'),
                'car_type_id' => $request->input('car_type_id'),
                'division_id' => $request->input('division_id'),
                'em_id' => $request->input('em_id'),
                'rank_id' => $request->input('rank_id'),
                'destination' => $request->input('destination'),
                'details' => $request->input('details'),
                'requested_by_user_id' => $request->input('requested_by_user_id'),
                'updated_by_user_id' => $request->input('requested_by_user_id'),
                'passenger_number' => $request->input('passenger_number'),
                'status_id' => 1// Waiting For Approve
            ]);
            if ($request->has('passengers')) {
                foreach ($request->input('passengers') as $newPassenger) {
                    if ($newPassenger['employeeID'] != null) {
                        $passenger[] = [
                            'car_request_id' => $carRequest->id,
                            'em_id' => $newPassenger['employeeID']
                        ];
                    }
                }
                if ($passenger != null) {
                    $addPassenger->insert($passenger);
                }
            }
        });
        return response()->json($result);
    }

    /*Update Car Request*/
    public function updateCarRequest(Request $request)
    {
        $result=DB::transaction(function() use ($request){
            $carRequest = CarRequest::where('id', $request->input('id'))->update([
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'start_time' => $request->input('start_time'),
                'end_time' => $request->input('end_time'),
                'car_type_id' => $request->input('car_type_id'),
                'division_id' => $request->input('division_id'),
                'em_id' => $request->input('em_id'),
                'rank_id' => $request->input('rank_id'),
                'destination' => $request->input('destination'),
                'details' => $request->input('details'),
                'requested_by_user_id' => $request->input('requested_by_user_id'),
                'updated_by_user_id' => $request->input('requested_by_user_id'),
                'passenger_number' => $request->input('passenger_number'),
            ]);
            if ($request->has('passenger')) {
                /*Delete All Passenger*/
                $passengers = [];
                $addPassenger = CarRequestPassenger::where('car_request_id', $request->input('id'))->delete();
                /*And Add All Passenger*/
                foreach ($request->input('passenger') as $emID) {
                    $passengers[] = [
                        'car_request_id' => $carRequest->id,
                        'em_id' => $emID
                    ];
                }
                $addPassenger->insert($passengers);
            }
        });
        return response()->json($result);
    }

    /*Delete Car Request*/
    public function deleteRequest(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            CarRequest::whereIn('id', $request->input('car_request_ids'))->delete();
            CarRequestPassenger::whereIn('car_request_id', $request->input('car_request_ids'))->delete();
        });
        return response()->json($result);
    }

}
