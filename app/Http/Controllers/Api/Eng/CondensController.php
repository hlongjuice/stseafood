<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\Condens;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CondensController extends Controller
{
    //Get Record
    public function getRecordByDate($date)
    {
        $records = Condens::whereDate('date', $date)
            ->orderBy('time_record', 'asc')
            ->get();
        return response()->json($records);
    }

    //Add Record
    public function addRecord(Request $request)
    {
        $result = Condens::create([
            'date' => $request->input('date'),
            'time_record' => $request->input('time_record'),
            'real_time_record' => $request->input('real_time_record'),
            'con2_w_meter'=>$request->input('con2_w_meter'),
            'con3_w_meter' =>$request->input('con3_w_meter'),
            'con5_meter_m5'=>$request->input('con5_meter_m5'),
            'con6_meter_m6'=>$request->input('con6_meter_m6'),
            'con7_meter_m7'=>$request->input('con7_meter_m7'),
            'con8_w_meter'=>$request->input('con8_w_meter')
        ]);
        return response()->json($result);
    }

    //Update Record
    public function updateRecord(Request $request)
    {
        $result = Condens::where('id',$request->input('id'))
            ->update([
                'date' => $request->input('date'),
                'time_record' => $request->input('time_record'),
                'real_time_record' => $request->input('real_time_record'),
                'con2_w_meter'=>$request->input('con2_w_meter'),
                'con3_w_meter' =>$request->input('con3_w_meter'),
                'con5_meter_m5'=>$request->input('con5_meter_m5'),
                'con6_meter_m6'=>$request->input('con6_meter_m6'),
                'con7_meter_m7'=>$request->input('con7_meter_m7'),
                'con8_w_meter'=>$request->input('con8_w_meter')
            ]);
        return response()->json($result);
    }
    //Delete Record
    public function deleteRecord($id){
        $result=Condens::where('id',$id)->delete();
        return response()->json($result);
    }
}
