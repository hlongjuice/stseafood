<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\Chlorine;
use App\Models\Eng\ChlorineLab;
use App\Models\Eng\EngDateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class ChlorineLabController extends Controller
{
    //Get Record
    public function getRecordByDate($date)
    {
        $records = EngDateTime::with('lab')->whereDate('date', $date)
            ->orderBy('time_record', 'asc')
            ->get();
        return response()->json($records);
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
