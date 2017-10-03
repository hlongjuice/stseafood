<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\_5x7;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class _5x7Controller extends Controller
{
    //Get Record
    public function getRecordByDate($date)
    {
        $dateInput= Carbon::createFromFormat('Y-m-d', $date);
//        Carbon::setTestNow($dateInput);
//        $yesterday=Carbon::yesterday()->toDateString();
        $yesterday=$dateInput->subDay(1)->toDateString();
        $last_yesterday_record=_5x7::whereDate('date',$yesterday)
            ->get()->sortBy('time_record', SORT_NATURAL)->values()->last();
        if($last_yesterday_record!=null){
            $last_yesterday_record->zero_time_record='0:00';
        }
        $records = _5x7::whereDate('date', $date)
            ->get()->sortBy('time_record', SORT_NATURAL)->values();
        $result=collect([
            'data'=>$records,
           'yesterday_meter'=>$last_yesterday_record,
            'yesterday'=>$yesterday,
            'date'=>$date
        ]);
        return response()->json($result);
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
