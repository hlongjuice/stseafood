<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\Chlorine;
use App\Models\Eng\ChlorineLab;
use App\Models\Eng\EngDateTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class ChlorineController extends Controller
{
    //Get Record
    public function getRecordByDate($date)
    {
        $dateInput= Carbon::createFromFormat('Y-m-d', $date);
//        Carbon::setTestNow($dateInput);
//        $yesterday=Carbon::yesterday()->toDateString();
        $yesterday=$dateInput->subDay(1)->toDateString();
        $last_yesterday_record=EngDateTime::with('chlorine','lab')->whereDate('date',$yesterday)
            ->get()->sortBy('time_record', SORT_NATURAL)->values()->last();
        if($last_yesterday_record!=null){
            $last_yesterday_record->zero_time_record='0:00';
        }
        $records = EngDateTime::with('chlorine', 'lab')->whereDate('date', $date)
            ->get()->sortBy('time_record', SORT_NATURAL)->values();
        $results=collect([
            'data'=>$records,
            'yesterday_meter'=>$last_yesterday_record,
            'yesterday'=>$yesterday,
            'date'=>$date
        ]);
        return response()->json($results);
    }

    //Add Record
    public function addRecord(Request $request)
    {
        $result=DB::transaction(function () use ($request){
            $dateTime = EngDateTime::where('date', $request->input('date'))
                ->where('time_record', $request->input('time_record'))
                ->first();
            if (!$dateTime) {
                $dateTime = EngDateTime::create([
                    'date' => $request->input('date'),
                    'time_record' => $request->input('time_record')
                ]);
            }
            Chlorine::create([
                'date_time_id' => $dateTime->id,
                'real_time_record' => $request->input('real_time_record'),
                'level' => $request->input('level'),
                'stroke' => $request->input('stroke'),
                'stroke_l' => $request->input('stroke_l'),
//            'lab'=>$request->input('lab')
            ]);
        });
        return response()->json($result);
    }

    //Update Record
    public function updateRecord(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $dateTime = EngDateTime::where('date', $request->input('date'))
                ->where('time_record', $request->input('time_record'))
                ->first();
            if (!$dateTime) {
                $dateTime = EngDateTime::create([
                    'date' => $request->input('date'),
                    'time_record' => $request->input('time_record')
                ]);
            }
            $chlorine= Chlorine::where('date_time_id', $dateTime->id)->first();
            if(!$chlorine){
                Chlorine::create([
                    'date_time_id' => $dateTime->id,
                    'real_time_record' => $request->input('real_time_record'),
                    'level' => $request->input('level'),
                    'stroke' => $request->input('stroke'),
                    'stroke_l' => $request->input('stroke_l'),
//                'lab'=>$request->input('lab')
                ]);
            }
            else{
                $chlorine->update([
                    'date_time_id' => $dateTime->id,
                    'real_time_record' => $request->input('real_time_record'),
                    'level' => $request->input('level'),
                    'stroke' => $request->input('stroke'),
                    'stroke_l' => $request->input('stroke_l'),
//                'lab'=>$request->input('lab')
                ]);
            }
        });
        return response()->json($result);
    }

    //Delete Record
    public function deleteRecord($id)
    {
        $result = Chlorine::where('date_time_id', $id)->delete();
        return response()->json($result);
    }
}
