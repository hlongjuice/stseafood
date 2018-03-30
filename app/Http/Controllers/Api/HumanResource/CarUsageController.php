<?php

namespace App\Http\Controllers\Api\HumanResource;

use App\Models\HumanResource\CarResponse;
use App\Models\HumanResource\CarUsage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class CarUsageController extends Controller
{
    /*Get Car Usage*/
    public function getByMonth(Request $request)
    {
        $carUsage = CarUsage::where('car_id', $request->input('car_id'))
            ->where('car_access_status_id', 3)
            ->whereYear('date_arrival', $request->input('year'))
            ->whereMonth('date_arrival', $request->input('month'))
            ->orderBy('date_arrival', 'desc')
            ->orderBy('time_arrival', 'desc')
            ->get();
        return response()->json($carUsage);
    }

    //Get By Year
    public function getByYear(Request $request){
        $carUsage = CarUsage::where('car_id', $request->input('car_id'))
            ->where('car_access_status_id', 3)
            ->whereYear('date_arrival', $request->input('year'))
            ->orderBy('date_arrival', 'desc')
            ->orderBy('time_arrival', 'desc')
            ->get();
        return response()->json($carUsage);
    }

}
