<?php

namespace App\Http\Controllers\Api\HumanResource;

use App\Models\HumanResource\CarResponse;
use App\Models\HumanResource\CarUsage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class CarAccessController extends Controller
{
    /*Get Car Departure || Arrival*/
    public function getCars($status)
    {
        $carDeparture = CarResponse::with('driver', 'division', 'employee', 'status', 'car.carType', 'carUsage')
            ->where('status_id', 3)
            ->where('car_access_status_id', $status)
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->paginate(100);
        return response()->json($carDeparture);
    }

    /*Car Departure*/
    public function addCarDeparture(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $carUsage = CarUsage::create([
                'car_id' => $request->input('car_id'),
                'car_access_status_id' => 2,
                'response_id' => $request->input('response_id'),
                'date_departure' => $request->input('date_departure'),
                'time_departure' => $request->input('time_departure'),
                'mile_start' => $request->input('mile_start'),
                'recorded_by_user_id' => $request->input('user_id')
            ]);
            CarResponse::where('id', $request->input('response_id'))
                ->update([
                    'car_access_status_id' => 2
                ]);
        });
        return response()->json($result);
    }

    /*Cancel Status*/
    public function cancelStatus($response_id)
    {
        $result = DB::transaction(function () use ($response_id) {
            $carResponse = CarResponse::where('id', $response_id)->first();
            $carResponse->car_access_status_id = $carResponse->car_access_status_id - 1;
            if ($carResponse->car_access_status_id == 1) {
                $carResponse->carUsage()->delete();
            }else{
                $carResponse->carUsage()->update([
                    'car_access_status_id'=>$carResponse->car_access_status_id
                ]);
            }
            $carResponse->save();
        });
        return response()->json($result);
    }

    /*Add Car Arrival*/
    public function addCarArrival(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $distance_per_litre = 0;
            $price_per_distance = 0;
            $carUsage = CarUsage::where('response_id', $request->input('response_id'))->first();
            $distance = $request->input('mile_end') - $carUsage->mile_start;
            if ($request->input('gas_fill') != 0 and $request->input('gas_fill') != null) {
                $distance_per_litre = $distance / $request->input('gas_fill');
                if ($distance != 0) {
                    $price_per_distance = $request->input('gas_total_price') / $distance;
                }
            }

            $carUsage->update([
                'car_access_status_id' => 3,
                'date_arrival' => $request->input('date_arrival'),
                'time_arrival' => $request->input('time_arrival'),
                'distance' => $distance,
                'distance_per_litre' => $distance_per_litre,
                'price_per_distance' => $price_per_distance,
                'mile_end' => $request->input('mile_end'),
                'gas_unit_price' => $request->input('gas_unit_price') ? $request->input('gas_unit_price') : 0,
                'gas_total_price' => $request->input('gas_total_price') ? $request->input('gas_total_price') : 0,
                'gas_fill' => $request->input('gas_fill') ? $request->input('gas_fill') : 0,
                'gas_station' => $request->input('gas_station')
            ]);
            CarResponse::where('id', $request->input('response_id'))
                ->update([
                    'car_access_status_id' => 3
                ]);
        });
        return response()->json($result);
    }

    /*Edit Car Access*/
    public function updateCarAccess(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $distance_per_litre = 0;
            $price_per_distance = 0;
            $carUsage = CarUsage::where('response_id', $request->input('response_id'))->first();
            $distance = $request->input('mile_end') - $carUsage->mile_start;
            if ($request->input('gas_fill') != 0 and $request->input('gas_fill') != null) {
                $distance_per_litre = $distance / $request->input('gas_fill');
                if ($distance != 0) {
                    $price_per_distance = $request->input('gas_total_price') / $distance;
                }
            }

            $carUsage->update([
                'date_departure' => $request->input('date_departure'),
                'time_departure' => $request->input('time_departure'),
                'mile_start' => $request->input('mile_start'),
                'date_arrival' => $request->input('date_arrival'),
                'time_arrival' => $request->input('time_arrival'),
                'distance' => $distance,
                'distance_per_litre' => $distance_per_litre,
                'price_per_distance' => $price_per_distance,
                'mile_end' => $request->input('mile_end'),
                'gas_unit_price' => $request->input('gas_unit_price') ? $request->input('gas_unit_price') : 0,
                'gas_total_price' => $request->input('gas_total_price') ? $request->input('gas_total_price') : 0,
                'gas_fill' => $request->input('gas_fill') ? $request->input('gas_fill') : 0,
                'gas_station' => $request->input('gas_station'),
                'updated_recorded_by_user_id' => $request->input('user_id')
            ]);
        });
        return response()->json($result);
    }
}
