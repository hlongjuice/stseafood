<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\RiverWater;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RiverWaterController extends Controller
{
    //Get Record
    public function getRecordByDate($date)
    {
        $records = RiverWater::whereDate('date', $date)
            ->orderBy('time_record', 'asc')
            ->get();
        return response()->json($records);
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
        $result = RiverWater::where('id',$request->input('id'))
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
    public function deleteRecord($id){
        $result=RiverWater::where('id',$id)->delete();
        return response()->json($result);
    }
}
