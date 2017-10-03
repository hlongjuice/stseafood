<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\RiverWater;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RiverWaterController extends Controller
{
    //Get Record
    public function getRecordByDate($date)
    {
        $dateInput = Carbon::createFromFormat('Y-m-d', $date);
//        Carbon::setTestNow($dateInput);
//        $yesterday = Carbon::yesterday()->toDateString();
        $yesterday=$dateInput->subDay(1)->toDateString();
        $last_yesterday_record = RiverWater::whereDate('date', $yesterday)
            ->get()->sortBy('time_record', SORT_NATURAL)->values()->last();
        if ($last_yesterday_record != null) {
            $last_yesterday_record->zero_time_record = '0:00';
        }
        $records = RiverWater::whereDate('date', $date)
            ->get()->sortBy('time_record', SORT_NATURAL)->values();
        $results = collect([
            'data' => $records,
            'yesterday' => $yesterday,
            'yesterday_meter' => $last_yesterday_record,
            'date'=>$date
        ]);
        return response()->json($results);
    }

    //Add Record
    public function addRecord(Request $request)
    {
        $result = RiverWater::create([
            'date' => $request->input('date'),
            'time_record' => $request->input('time_record'),
            'real_time_record' => $request->input('real_time_record'),
            'bar' => $request->input('bar'),
            'level' => $request->input('level')
        ]);
        return response()->json($result);
    }

    //Update Record
    public function updateRecord(Request $request)
    {
        $result = RiverWater::where('id', $request->input('id'))
            ->update([
                'date' => $request->input('date'),
                'time_record' => $request->input('time_record'),
                'real_time_record' => $request->input('real_time_record'),
                'bar' => $request->input('bar'),
                'level' => $request->input('level')
            ]);
        return response()->json($result);
    }

    //Delete Record
    public function deleteRecord($id)
    {
        $result = RiverWater::where('id', $id)->delete();
        return response()->json($result);
    }
}
