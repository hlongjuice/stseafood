<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\Chlorine;
use App\Models\Eng\ChlorineLab;
use App\Models\Eng\EngDateTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class ChlorineLabController extends Controller
{
    //Get Record
    public function getRecordByDate($date)
    {
        $dateInput= Carbon::createFromFormat('Y-m-d', $date);
//        Carbon::setTestNow($dateInput);
//        $yesterday=Carbon::yesterday()->toDateString();
        $yesterday=$dateInput->subDay(1)->toDateString();
        $last_yesterday_record=EngDateTime::with('lab')->whereDate('date',$yesterday)
            ->get()->sortBy('time_record', SORT_NATURAL)->values()->last();
        if($last_yesterday_record!=null){
            $last_yesterday_record->zero_time_record='0:00';
        }
        $records = EngDateTime::with('lab')->whereDate('date', $date)
            ->get()->sortBy('time_record', SORT_NATURAL)->values();
        $results=collect([
            'data'=>$records,
            'yesterday'=>$yesterday,
            'yesterday_meter'=>$last_yesterday_record,
            'date'=>$date
        ]);
        return response()->json($results);
    }

    //Add Record
    public function addRecord(Request $request)
    {
        $result=DB::transaction(function() use ($request){
            $dateTime = EngDateTime::where('date', $request->input('date'))
                ->where('time_record', $request->input('time_record'))
                ->first();
            if (!$dateTime) {
                $dateTime = EngDateTime::create([
                    'date' => $request->input('date'),
                    'time_record' => $request->input('time_record')
                ]);
            }
            ChlorineLab::create([
                'date_time_id'=>$dateTime->id,
                'date' => $request->input('date'),
                'time_record' => $request->input('time_record'),
                'real_time_record' => $request->input('real_time_record'),
                'chlorine'=>$request->input('chlorine'),
                'p1'=>$request->input('p1'),
                'p2'=>$request->input('p2')
            ]);
        });

        return response()->json($result);
    }

    //Update Record
    public function updateRecord(Request $request)
    {
        $result=DB::transaction(function() use ($request){
            $dateTime = EngDateTime::where('date', $request->input('date'))
                ->where('time_record', $request->input('time_record'))
                ->first();
            if (!$dateTime) {
                $dateTime = EngDateTime::create([
                    'date' => $request->input('date'),
                    'time_record' => $request->input('time_record')
                ]);
            }
            $chlorineLab= ChlorineLab::where('date_time_id',$dateTime->id)->first();
            if(!$chlorineLab){
                ChlorineLab::create([
                    'date_time_id'=>$dateTime->id,
                    'real_time_record' => $request->input('real_time_record'),
                    'chlorine'=>$request->input('chlorine'),
                    'p1'=>$request->input('p1'),
                    'p2'=>$request->input('p2')
                ]);
            }
            else{
                $chlorineLab->update([
                    'date_time_id'=>$dateTime->id,
                    'real_time_record' => $request->input('real_time_record'),
                    'chlorine'=>$request->input('chlorine'),
                    'p1'=>$request->input('p1'),
                    'p2'=>$request->input('p2')
                ]);
            }
        });

        return response()->json($result);
    }
    //Delete Record
    public function deleteRecord($id){
        $result=ChlorineLab::where('date_time_id',$id)->delete();
        return response()->json($result);
    }
}
