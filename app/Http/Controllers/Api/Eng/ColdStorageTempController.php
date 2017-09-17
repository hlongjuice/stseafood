<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\ColdStorageTemp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ColdStorageTempController extends Controller
{
    //Get Record
    public function getRecordByDate($date)
    {
        $records = ColdStorageTemp::whereDate('date', $date)
            ->get()->sortBy('time_record', SORT_NATURAL)->values();
        return response()->json($records);
    }

    //Add Record
    public function addRecord(Request $request)
    {
        $result = ColdStorageTemp::create([
            'date' => $request->input('date'),
            'time_record' => $request->input('time_record'),
            'real_time_record' => $request->input('real_time_record'),
            'cs1_rm'=>$request->input('cs1_rm'),
            'cs1_c_1_1'=>$request->input('cs1_c_1_1'),
            'cs1_c_1_2'=>$request->input('cs1_c_1_2'),
            'cs1_c_1_3'=>$request->input('cs1_c_1_3'),
            'cs1_defrost_status'=>$request->input('cs1_defrost_status'),
            'cs2_rm'=>$request->input('cs2_rm'),
            'cs2_defrost_status'=>$request->input('cs2_defrost_status')
        ]);
        return response()->json($result);
    }

    //Update Record
    public function updateRecord(Request $request)
    {
        $result = ColdStorageTemp::where('id',$request->input('id'))
            ->update([
                'date' => $request->input('date'),
                'time_record' => $request->input('time_record'),
                'real_time_record' => $request->input('real_time_record'),
                'cs1_rm'=>$request->input('cs1_rm'),
                'cs1_c_1_1'=>$request->input('cs1_c_1_1'),
                'cs1_c_1_2'=>$request->input('cs1_c_1_2'),
                'cs1_c_1_3'=>$request->input('cs1_c_1_3'),
                'cs1_defrost_status'=>$request->input('cs1_defrost_status'),
                'cs2_rm'=>$request->input('cs2_rm'),
                'cs2_defrost_status'=>$request->input('cs2_defrost_status')
            ]);
        return response()->json($result);
    }
    //Delete Record
    public function deleteRecord($id){
        $result=ColdStorageTemp::where('id',$id)->delete();
        return response()->json($result);
    }
}
