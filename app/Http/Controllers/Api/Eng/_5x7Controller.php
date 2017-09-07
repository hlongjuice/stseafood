<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\_5x7;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class _5x7Controller extends Controller
{
    //Get Record
    public function getRecordByDate($date)
    {
        $records = _5x7::whereDate('date', $date)
            ->orderBy('time_record', 'asc')
            ->get();
        return response()->json($records);
    }

    //Add Record
    public function addRecord(Request $request)
    {
        $result = _5x7::create([
            'date' => $request->input('date'),
            'time_record' => $request->input('time_record'),
            'real_time_record' => $request->input('real_time_record'),
            'level' => $request->input('level')
        ]);
        return response()->json($result);
    }

    //Update Record
    public function updateRecord(Request $request)
    {
        $result = _5x7::where('id',$request->input('id'))
            ->update([
                'date' => $request->input('date'),
                'time_record' => $request->input('time_record'),
                'real_time_record' => $request->input('real_time_record'),
                'level' => $request->input('level')
            ]);
        return response()->json($result);
    }
    //Delete Record
    public function deleteRecord($id){
        $result=_5x7::where('id',$id)->delete();
        return response()->json($result);
    }
}
