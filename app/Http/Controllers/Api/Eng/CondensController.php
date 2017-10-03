<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\Condens;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CondensController extends Controller
{
    //Get Record
    public function getRecordByDate($date)
    {
        $con2_w_meter_used = 0;
        $con3_w_meter_used = 0;
        $con5_meter_m5_used = 0;
        $con6_meter_m6_used = 0;
        $con7_meter_m7_used = 0;
        $con8_w_meter_used = 0;
        //Date
        $dateInput = Carbon::createFromFormat('Y-m-d', $date);
//        Carbon::setTestNow($dateInput);
//        $yesterday = Carbon::yesterday()->toDateString();
        $yesterday=$dateInput->subDay(1)->toDateString();
        $last_yesterday_used = Condens::whereDate('date', $yesterday)
            ->get()
            ->sortBy('time_record', SORT_NATURAL)->values()->last();
        if ($last_yesterday_used != null) {
            $last_yesterday_used->zero_time_record = '0:00';
        }
        $records = Condens::whereDate('date', $date)
            ->get()->sortBy('time_record', SORT_NATURAL)->values();
        if ($records->count() > 0 && $last_yesterday_used != null) {
            $last_record = $records->last();
            $con2_w_meter_used = CalculateController::getDailyUsed($last_record->con2_w_meter, $last_yesterday_used->con2_w_meter);
            $con3_w_meter_used = CalculateController::getDailyUsed($last_record->con3_w_meter, $last_yesterday_used->con3_w_meter);
            $con5_meter_m5_used = CalculateController::getDailyUsed($last_record->con5_meter_m5, $last_yesterday_used->con5_meter_m5);
            $con6_meter_m6_used = CalculateController::getDailyUsed($last_record->con6_meter_m6, $last_yesterday_used->con6_meter_m6);
            $con7_meter_m7_used = CalculateController::getDailyUsed($last_record->con7_meter_m7, $last_yesterday_used->con7_meter_m7);
            $con8_w_meter_used = CalculateController::getDailyUsed($last_record->con8_w_meter, $last_yesterday_used->con8_w_meter);
        }
        $results = collect([
            'data' => $records,
            'con2_w_meter_used' => $con2_w_meter_used,
            'con3_w_meter_used' => $con3_w_meter_used,
            'con5_meter_m5_used' => $con5_meter_m5_used,
            'con6_meter_m6_used' => $con6_meter_m6_used,
            'con7_meter_m7_used' => $con7_meter_m7_used,
            'con8_w_meter_used' => $con8_w_meter_used,
            'yesterday'=>$yesterday,
            'yesterday_meter'=>$last_yesterday_used,
            'date'=>$date
        ]);
        return response()->json($results);
    }

    //Add Record
    public function addRecord(Request $request)
    {
        $result = Condens::create([
            'date' => $request->input('date'),
            'time_record' => $request->input('time_record'),
            'real_time_record' => $request->input('real_time_record'),
            'con2_w_meter' => $request->input('con2_w_meter'),
            'con3_w_meter' => $request->input('con3_w_meter'),
            'con5_meter_m5' => $request->input('con5_meter_m5'),
            'con6_meter_m6' => $request->input('con6_meter_m6'),
            'con7_meter_m7' => $request->input('con7_meter_m7'),
            'con8_w_meter' => $request->input('con8_w_meter')
        ]);
        return response()->json($result);
    }

    //Update Record
    public function updateRecord(Request $request)
    {
        $result = Condens::where('id', $request->input('id'))
            ->update([
                'date' => $request->input('date'),
                'time_record' => $request->input('time_record'),
                'real_time_record' => $request->input('real_time_record'),
                'con2_w_meter' => $request->input('con2_w_meter'),
                'con3_w_meter' => $request->input('con3_w_meter'),
                'con5_meter_m5' => $request->input('con5_meter_m5'),
                'con6_meter_m6' => $request->input('con6_meter_m6'),
                'con7_meter_m7' => $request->input('con7_meter_m7'),
                'con8_w_meter' => $request->input('con8_w_meter')
            ]);
        return response()->json($result);
    }

    //Delete Record
    public function deleteRecord($id)
    {
        $result = Condens::where('id', $id)->delete();
        return response()->json($result);
    }

    //Monthly Result
    public static function getMonthlyResult($year, $month)
    {
        $con2_w_meter_used = 0;
        $con3_w_meter_used = 0;
        $con5_meter_m5_used = 0;
        $con6_meter_m6_used = 0;
        $con7_meter_m7_used = 0;
        $con8_w_meter_used = 0;
        //Last Month
        $dateInput = Carbon::createFromFormat('Y-m-d', $year . '-' . $month . '-1');
//        Carbon::setTestNow($dateInput);
//        $last_month = Carbon::yesterday()->month;
//        $last_year = Carbon::yesterday()->year;
        $last_month=$dateInput->subDay(1)->month;
        $last_year=$dateInput->subDay(1)->year;
        $last_month_records = Condens::whereYear('date', $last_year)
            ->whereMonth('date', $last_month)
            ->get()->sortBy('date', SORT_NATURAL)->values()->groupBy('date');
        //End Last Month
        //Get Current Month
        $records = Condens::whereYear('date', $year)
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
                        $con2_w_meter_used = CalculateController::getDailyUsed($result['last_record']['con2_w_meter'], $last_month_records['con2_w_meter']);
                        $con3_w_meter_used = CalculateController::getDailyUsed($result['last_record']['con3_w_meter'], $last_month_records['con3_w_meter']);
                        $con5_meter_m5_used = CalculateController::getDailyUsed($result['last_record']['con5_meter_m5'], $last_month_records['con5_meter_m5']);
                        $con6_meter_m6_used = CalculateController::getDailyUsed($result['last_record']['con6_meter_m6'], $last_month_records['con6_meter_m6']);
                        $con7_meter_m7_used = CalculateController::getDailyUsed($result['last_record']['con7_meter_m7'], $last_month_records['con7_meter_m7']);
                        $con8_w_meter_used = CalculateController::getDailyUsed($result['last_record']['con8__w_meter'], $last_month_records['con8__w_meter']);
                    }
                } else {
                    $con2_w_meter_used = CalculateController::getDailyUsed($results[$i]['last_record']['con2_w_meter'], $results[$i - 1]['last_record']['con2_w_meter']);
                    $con3_w_meter_used = CalculateController::getDailyUsed($results[$i]['last_record']['con3_w_meter'], $results[$i - 1]['last_record']['con3_w_meter']);
                    $con5_meter_m5_used = CalculateController::getDailyUsed($results[$i]['last_record']['con5_meter_m5'], $results[$i - 1]['last_record']['con5_meter_m5']);
                    $con6_meter_m6_used = CalculateController::getDailyUsed($results[$i]['last_record']['con6_meter_m6'], $results[$i - 1]['last_record']['con6_meter_m6']);
                    $con7_meter_m7_used = CalculateController::getDailyUsed($results[$i]['last_record']['con7_meter_m7'], $results[$i - 1]['last_record']['con7_meter_m7']);
                    $con8_w_meter_used = CalculateController::getDailyUsed($results[$i]['last_record']['con8_w_meter'], $results[$i - 1]['last_record']['con8_w_meter']);

                }
                $result->put('used', [
                    'con2_w_meter_used' => $con2_w_meter_used,
                    'con3_w_meter_used' => $con3_w_meter_used,
                    'con5_meter_m5_used' => $con5_meter_m5_used,
                    'con6_meter_m6_used' => $con6_meter_m6_used,
                    'con7_meter_m7_used' => $con7_meter_m7_used,
                    'con8_w_meter_used' => $con8_w_meter_used
                ]);
                $i++;
            }
        }
        $results = collect([
            'data' => $results,
            'init' => [
                'con2_w_meter_used' => 0,
                'con3_w_meter_used' => 0,
                'con5_meter_m5_used' => 0,
                'con6_meter_m6_used' => 0,
                'con7_meter_m7_used' => 0,
                'con8_w_meter_used' => 0
            ]
        ]);
        return $results;
    }

}
