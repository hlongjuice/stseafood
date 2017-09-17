<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\EngDateTime;
use App\Models\Eng\WaterFiltration;
use App\Models\Eng\WaterMeter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WaterFiltrationController extends Controller
{
    //Get Record
    public function getRecordByDate($date)
    {
        $plant1_mm_1_used=0;
        $plant2_mm_2_used=0;
        $dateInput = Carbon::createFromFormat('Y-m-d', $date);
        Carbon::setTestNow($dateInput);
        $yesterday = Carbon::yesterday()->toDateString();
        $last_yesterday_used = WaterFiltration::whereDate('date', $yesterday)
            ->get()
            ->sortBy('time_record', SORT_NATURAL)->values()->last();
        $records = WaterFiltration::whereDate('date', $date)
            ->get()->sortBy('time_record', SORT_NATURAL)->values();
        //Water Meter
        $water_meters = WaterMeter::whereDate('date', $date)
            ->get()->sortBy('time_record', SORT_NATURAL)->values();
        $labs = EngDateTime::with('lab')
            ->whereDate('date', $date)
            ->get()->sortBy('time_record', SORT_NATURAL)->values();
        $i = 0;
        foreach ($records as $record) {
            if ($i == 0) {
                $record->p1_flow = CalculateController::getUsed($record->p1_mm1, ($last_yesterday_used) ? $last_yesterday_used->p1_mm1 : null);
                $record->p2_flow = CalculateController::getUsed($record->p2_mm2, ($last_yesterday_used) ? $last_yesterday_used->p2_mm2 : null);
                $record->mm_1_2_flow = $record->p1_mm1 + $record->p2_mm2;
            } else {
                $record->p1_flow = CalculateController::getUsed($record->p1_mm1, $records[$i - 1]->p1_mm1);
                $record->p2_flow = CalculateController::getUsed($record->p2_mm2, $records[$i - 1]->p2_mm2);
            }
            //If has Water Meter
            if ($water_meters) {
                for ($j = 0; $j < $water_meters->count(); $j++) {
                    if ($record->time_record == $water_meters[$j]->time_record) {
                        $record->mm_4_5_6_flow = $water_meters[$j]->mm_4 + $water_meters[$j]->mm_5 + $water_meters[$j]->mm_6;
                        $water_meters->splice($j, 1);
                    }
                }
            }
            //If Has Lab
            if ($labs) {
                for ($k = 0; $k < $labs->count(); $k++) {
                    if ($record->time_record == $labs[$k]->time_record)
                        $record->lab_p1 = $labs[$k]->lab->p1;
                    $record->lab_p2 = $labs[$k]->lab->p2;
                    $labs->splice($k, 1);
                }
            }
            $i++;
        }
        //Get Daily Used
        if($records->count()>0 && $last_yesterday_used!=null){
            $last_record=$records->last();
            $plant1_mm_1_used=CalculateController::getDailyUsed($last_record->p1_mm1,$last_yesterday_used->p1_mm1);
            $plant2_mm_2_used=CalculateController::getDailyUsed($last_record->p2_mm2,$last_yesterday_used->p2_mm2);
        }
        $results=collect([
           'data'=>$records,
            'plant1_mm_1_used'=>$plant1_mm_1_used,
            'plant2_mm_2_used'=>$plant2_mm_2_used
        ]);
        return response()->json($results);
    }

    //Add Record
    public function addRecord(Request $request)
    {
        $result = WaterFiltration::create([
            'date' => $request->input('date'),
            'time_record' => $request->input('time_record'),
            'real_time_record' => $request->input('real_time_record'),
            'raw_w_pump1' => $request->input('raw_w_pump1'),
            'raw_w_pump2' => $request->input('raw_w_pump2'),
            'raw_w_pump3' => $request->input('raw_w_pump3'),
            'p1_mm1' => $request->input('p1_mm1'),
            'p2_mm2' => $request->input('p2_mm2'),
            'chem_pump1' => $request->input('chem_pump1'),
            'chem_pump2' => $request->input('chem_pump2'),
            'silt_pump1' => $request->input('silt_pump1'),
            'silt_pump2' => $request->input('silt_pump2')
        ]);
        return response()->json($result);
    }

    //Update Record
    public function updateRecord(Request $request)
    {
        $result = WaterFiltration::where('id', $request->input('id'))
            ->update([
                'date' => $request->input('date'),
                'time_record' => $request->input('time_record'),
                'real_time_record' => $request->input('real_time_record'),
                'raw_w_pump1' => $request->input('raw_w_pump1'),
                'raw_w_pump2' => $request->input('raw_w_pump2'),
                'raw_w_pump3' => $request->input('raw_w_pump3'),
                'p1_mm1' => $request->input('p1_mm1'),
                'p2_mm2' => $request->input('p2_mm2'),
                'chem_pump1' => $request->input('chem_pump1'),
                'chem_pump2' => $request->input('chem_pump2'),
                'silt_pump1' => $request->input('silt_pump1'),
                'silt_pump2' => $request->input('silt_pump2')
            ]);
        return response()->json($result);
    }

    //Delete Record
    public function deleteRecord($id)
    {
        $result = WaterFiltration::where('id', $id)->delete();
        return response()->json($result);
    }
    //Get Monthly Result
    public static function getMonthlyResult($year, $month)
    {
        $p1_mm1_used = 0;
        $p2_mm2_used = 0;
        //Last Month
        $dateInput = Carbon::createFromFormat('Y-m-d', $year . '-' . $month . '-1');
        Carbon::setTestNow($dateInput);
        $last_month = Carbon::yesterday()->month;
        $last_year = Carbon::yesterday()->year;
        $last_month_records = WaterFiltration::whereYear('date', $last_year)
            ->whereMonth('date', $last_month)
            ->get()->sortBy('date', SORT_NATURAL)->values()->groupBy('date');
        //End Last Month
        //Get Current Month
        $records = WaterFiltration::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()->sortBy('date', SORT_NATURAL)->values()->groupBy('date');

        $results = collect([]);
        if ($records->count() > 0) {
            foreach ($records as $key => $values) {
                $last_record = $values->sortBy('time_record', SORT_NATURAL)->values()->last();
                $record = collect([
                    'date' => $key,
                    'last_record' => $last_record
                ]);
                $results->push($record);
            }
            $i = 0;
            foreach ($results as $result) {
                if ($i == 0) {
                    if( $last_month_records->count() >0){
                        $last_month_records=$last_month_records->last()->last();
                        $p1_mm1_used=CalculateController::getDailyUsed($result['last_record']['p1_mm1'],$last_month_records['p1_mm1']);
                        $p2_mm2_used=CalculateController::getDailyUsed($result['last_record']['p2_mm2'],$last_month_records['p2_mm2']);
                    }
                }else{
                    $p1_mm1_used=CalculateController::getDailyUsed($results[$i]['last_record']['p1_mm1'],$results[$i-1]['last_record']['p1_mm1']);
                    $p2_mm2_used=CalculateController::getDailyUsed($results[$i]['last_record']['p2_mm2'],$results[$i-1]['last_record']['p2_mm2']);

                }
                $result->put('used',[
                    'p1_mm1_used'=>$p1_mm1_used,
                    'p2_mm2_used'=>$p2_mm2_used
                ]);
                $i++;
            }
        }
        $results=collect([
           'data'=>$results,
            'init'=>[
                'p1_mm1_used'=>0,
                'p2_mm2_used'=>0
            ]
        ]);
        return $results;
    }

}
