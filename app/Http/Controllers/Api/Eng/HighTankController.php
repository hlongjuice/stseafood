<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\HighTank;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HighTankController extends Controller
{
    //Get Record
    public function getRecordByDate($date)
    {
        $records = HighTank::whereDate('date', $date)
            ->get()->sortBy('time_record', SORT_NATURAL)->values();
        return response()->json($records);
    }

    //Add Record
    public function addRecord(Request $request)
    {
        $result = HighTank::create([
            'date' => $request->input('date'),
            'time_record' => $request->input('time_record'),
            'real_time_record' => $request->input('real_time_record'),
            'level' => $request->input('level'),
            'pump'=>$request->input('pump')
        ]);
        return response()->json($result);
    }

    //Update Record
    public function updateRecord(Request $request)
    {
        $result = HighTank::where('id',$request->input('id'))
            ->update([
                'date' => $request->input('date'),
                'time_record' => $request->input('time_record'),
                'real_time_record' => $request->input('real_time_record'),
                'level' => $request->input('level'),
                'pump'=>$request->input('pump')
            ]);
        return response()->json($result);
    }
    //Delete Record
    public function deleteRecord($id){
        $result=HighTank::where('id',$id)->delete();
        return response()->json($result);
    }
}
