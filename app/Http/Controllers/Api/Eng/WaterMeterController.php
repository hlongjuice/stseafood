<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\WaterMeter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WaterMeterController extends Controller
{
    //Get Record
    public function getRecordByDate($date)
    {
        $records = WaterMeter::whereDate('date', $date)
            ->orderBy('time_record', 'asc')
            ->get();
        return response()->json($records);
    }

    //Add Record
    public function addRecord(Request $request)
    {
        $result = WaterMeter::create([
            'date' => $request->input('date'),
            'time_record' => $request->input('time_record'),
            'real_time_record' => $request->input('real_time_record'),
            'mm_4' => $request->input('mm_4'),
            'mm_5' => $request->input('mm_5'),
            'mm_6'=>$request->input('mm_6')
        ]);
        return response()->json($result);
    }

    //Update Record
    public function updateRecord(Request $request)
    {
        $result = WaterMeter::where('id',$request->input('id'))
            ->update([
                'date' => $request->input('date'),
                'time_record' => $request->input('time_record'),
                'real_time_record' => $request->input('real_time_record'),
                'mm_4' => $request->input('mm_4'),
                'mm_5' => $request->input('mm_5'),
                'mm_6'=>$request->input('mm_6')
            ]);
        return response()->json($result);
    }
    //Delete Record
    public function deleteRecord($id){
        $result=WaterMeter::where('id',$id)->delete();
        return response()->json($result);
    }
}
