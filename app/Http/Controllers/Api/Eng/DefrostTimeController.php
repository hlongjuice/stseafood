<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\DefrostTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DefrostTimeController extends Controller
{
    //Get Record
    public function getRecordByDate($date)
    {
        $records = DefrostTime::orderBy('time_record', 'asc')
            ->get();
        return response()->json($records);
    }

    //Add Record
    public function addRecord(Request $request)
    {
        $result = DefrostTime::create([
            'time_record'=>$request->input('time_record')
        ]);
        return response()->json($result);
    }

    //Update Record
    public function updateRecord(Request $request)
    {
        $result = DefrostTime::where('id',$request->input('id'))
            ->update([
                'time_record' => $request->input('time_record'),
            ]);
        return response()->json($result);
    }
    //Delete Record
    public function deleteRecord($id){
        $result=DefrostTime::where('id',$id)->delete();
        return response()->json($result);
    }
}
