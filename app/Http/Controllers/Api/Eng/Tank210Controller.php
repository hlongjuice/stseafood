<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\Tank210;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Tank210Controller extends Controller
{
    //Get Record
    public function getRecordByDate($date)
    {
        $records = Tank210::whereDate('date', $date)
            ->orderBy('time_record', 'asc')
            ->get();
        return response()->json($records);
    }

    //Add Record
    public function addRecord(Request $request)
    {
        $result = Tank210::create([
            'date' => $request->input('date'),
            'time_record' => $request->input('time_record'),
            'real_time_record' => $request->input('real_time_record'),
            'mm_3' => $request->input('mm_3'),
            'level' => $request->input('level')
        ]);
        return response()->json($result);
    }

    //Update Record
    public function updateRecord(Request $request)
    {
        $result = Tank210::where('id',$request->input('id'))
        ->update([
            'date' => $request->input('date'),
            'time_record' => $request->input('time_record'),
            'real_time_record' => $request->input('real_time_record'),
            'mm_3' => $request->input('mm_3'),
            'level' => $request->input('level')
        ]);
        return response()->json($result);
    }
    //Delete Record
    public function deleteRecord($id){
        $result=Tank210::where('id',$id)->delete();
        return response()->json($result);
    }
}
