<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\ColdStorageTemp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ColdStorageTempController extends Controller
{
    //Get Record
    public function getRecordByDate($date)
    {
        $dateInput = Carbon::createFromFormat('Y-m-d', $date);
//        Carbon::setTestNow($dateInput);
//        $yesterday = Carbon::yesterday()->toDateString();
        $yesterday=$dateInput->subDay(1)->toDateString();
        $last_yesterday_record = ColdStorageTemp::whereDate('date', $yesterday)
            ->get()->sortBy('time_record', SORT_NATURAL)->values()->last();
        if ($last_yesterday_record != null) {
            $last_yesterday_record->zero_time_record = '0:00';
        }
        $records = ColdStorageTemp::whereDate('date', $date)
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
        $result = ColdStorageTemp::create([
            'date' => $request->input('date'),
            'time_record' => $request->input('time_record'),
            'real_time_record' => $request->input('real_time_record'),
            'cs1_rm' => $request->input('cs1_rm'),
            'cs1_c_1_1' => $request->input('cs1_c_1_1'),
            'cs1_c_1_2' => $request->input('cs1_c_1_2'),
            'cs1_c_1_3' => $request->input('cs1_c_1_3'),
            'cs1_defrost_status' => $request->input('cs1_defrost_status'),
            'cs2_rm' => $request->input('cs2_rm'),
            'cs2_defrost_status' => $request->input('cs2_defrost_status')
        ]);
        return response()->json($result);
    }

    //Update Record
    public function updateRecord(Request $request)
    {
        $result = ColdStorageTemp::where('id', $request->input('id'))
            ->update([
                'date' => $request->input('date'),
                'time_record' => $request->input('time_record'),
                'real_time_record' => $request->input('real_time_record'),
                'cs1_rm' => $request->input('cs1_rm'),
                'cs1_c_1_1' => $request->input('cs1_c_1_1'),
                'cs1_c_1_2' => $request->input('cs1_c_1_2'),
                'cs1_c_1_3' => $request->input('cs1_c_1_3'),
                'cs1_defrost_status' => $request->input('cs1_defrost_status'),
                'cs2_rm' => $request->input('cs2_rm'),
                'cs2_defrost_status' => $request->input('cs2_defrost_status')
            ]);
        return response()->json($result);
    }

    //Delete Record
    public function deleteRecord($id)
    {
        $result = ColdStorageTemp::where('id', $id)->delete();
        return response()->json($result);
    }
}
