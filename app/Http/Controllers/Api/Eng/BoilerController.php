<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\Boiler;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BoilerController extends Controller
{
    //Get Record
    public function getRecordByDate($date)
    {
        $boiler1_meter_used = 0;
        $boiler2_meter_used = 0;
        //Date
        $dateInput = Carbon::createFromFormat('Y-m-d', $date);
//        Carbon::setTestNow($dateInput);
//        $yesterday = Carbon::yesterday()->toDateString();
        $yesterday=$dateInput->subDay(1)->toDateString();
        $last_yesterday_used = Boiler::whereDate('date', $yesterday)
            ->get()->sortBy('time_record', SORT_NATURAL)->values()->last();
        if($last_yesterday_used!=null){
            $last_yesterday_used->zero_time_record='0:00';
        }
        $records = Boiler::whereDate('date', $date)
            ->get()->sortBy('time_record', SORT_NATURAL)->values();
        if ($records->count() > 0 && $last_yesterday_used != null) {
            $last_record = $records->last();
            $boiler1_meter_used = CalculateController::getDailyUsed($last_record->boiler1_meter, $last_yesterday_used->boiler1_meter);
            $boiler2_meter_used = CalculateController::getDailyUsed($last_record->boiler2_meter, $last_yesterday_used->boiler2_meter);
        }
        $results = collect([
            'data' => $records,
            'boiler1_meter_used' => $boiler1_meter_used,
            'boiler2_meter_used' => $boiler2_meter_used,
            'yesterday'=>$yesterday,
            'yesterday_meter'=>$last_yesterday_used,
            'date'=>$date
        ]);
        return response()->json($results);
    }

    //Add Record
    public function addRecord(Request $request)
    {
        $result = Boiler::create([
            'date' => $request->input('date'),
            'time_record' => $request->input('time_record'),
            'real_time_record' => $request->input('real_time_record'),
            'boiler1' => $request->input('boiler1'),
            'boiler1_meter' => $request->input('boiler1_meter'),
            'boiler1_tank_l' => $request->input('boiler1_tank_l'),
            'boiler2' => $request->input('boiler2'),
            'boiler2_meter' => $request->input('boiler2_meter'),
            'boiler2_tank_l' => $request->input('boiler2_tank_l'),
        ]);
        return response()->json($result);
    }

    //Update Record
    public function updateRecord(Request $request)
    {
        $result = Boiler::where('id', $request->input('id'))
            ->update([
                'date' => $request->input('date'),
                'time_record' => $request->input('time_record'),
                'real_time_record' => $request->input('real_time_record'),
                'boiler1' => $request->input('boiler1'),
                'boiler1_meter' => $request->input('boiler1_meter'),
                'boiler1_tank_l' => $request->input('boiler1_tank_l'),
                'boiler2' => $request->input('boiler2'),
                'boiler2_meter' => $request->input('boiler2_meter'),
                'boiler2_tank_l' => $request->input('boiler2_tank_l'),
            ]);
        return response()->json($result);
    }

    //Delete Record
    public function deleteRecord($id)
    {
        $result = Boiler::where('id', $id)->delete();
        return response()->json($result);
    }

    //Get Monthly Result
    public static function getMonthlyResult($year, $month)
    {
        $boiler1_meter_used = 0;
        $boiler2_meter_used = 0;
        //Last Month
        $dateInput = Carbon::createFromFormat('Y-m-d', $year . '-' . $month . '-1');
//        Carbon::setTestNow($dateInput);
//        $last_month = Carbon::yesterday()->month;
//        $last_year = Carbon::yesterday()->year;
        $last_month=$dateInput->subDay(1)->month;
        $last_year=$dateInput->subDay(1)->year;
        $last_month_records = Boiler::whereYear('date', $last_year)
            ->whereMonth('date', $last_month)
            ->get()->sortBy('date', SORT_NATURAL)->values()->groupBy('date');
        //End Last Month
        //Get Current Month
        $records = Boiler::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()->sortBy('date', SORT_NATURAL)->values()->groupBy('date');

        $results = collect([]);
        if ($records->count() > 0) {
            foreach ($records as $key => $values) {
                $last_record = $values->sortBy('time_record', SORT_NATURAL)->values()->last();
                $record = collect([
                    'date' => $key,
                    'last_record' => $last_record,
                ]);
                $results->push($record);
            }
            $i = 0;
            foreach ($results as $result) {
                if ($i == 0) {
                    if ($last_month_records->count() > 0) {
                        $last_month_records = $last_month_records->last()->last();
                        $boiler1_meter_used = CalculateController::getDailyUsed($result['last_record']['boiler1_meter'], $last_month_records['boiler1_meter']);
                        $boiler2_meter_used = CalculateController::getDailyUsed($result['last_record']['boiler2_meter'], $last_month_records['boiler2_meter']);
                    }
                } else {
                    $boiler1_meter_used = CalculateController::getDailyUsed($results[$i]['last_record']['boiler1_meter'], $results[$i - 1]['last_record']['boiler1_meter']);
                    $boiler2_meter_used = CalculateController::getDailyUsed($results[$i]['last_record']['boiler2_meter'], $results[$i - 1]['last_record']['boiler2_meter']);

                }
                $result->put('used', [
                    'boiler1_meter_used' => $boiler1_meter_used,
                    'boiler2_meter_used' => $boiler2_meter_used
                ]);
                $i++;
            }
        }
        $results=collect([
            'data'=>$results,
            'init'=>[
                'boiler1_meter_used' => 0,
                'boiler2_meter_used' => 0
            ]
        ]);
        return $results;
    }


}
