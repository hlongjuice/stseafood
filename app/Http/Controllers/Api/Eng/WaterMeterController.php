<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\WaterMeter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WaterMeterController extends Controller
{
    //Get Record
    public function getRecordByDate($date)
    {
        $mm_4_used = 0;
        $mm_5_used = 0;
        $mm_6_used = 0;
        //Date
        $dateInput = Carbon::createFromFormat('Y-m-d', $date);
//        Carbon::setTestNow($dateInput);
//        $yesterday = Carbon::yesterday()->toDateString();
        $yesterday = $dateInput->subDay(1)->toDateString();
        $last_yesterday_meter = WaterMeter::whereDate('date', $yesterday)
            ->get()
            ->sortBy('time_record', SORT_NATURAL)->values()->last();
        if ($last_yesterday_meter != null) {
            $last_yesterday_meter->zero_time_record = '0:00';
        }
        $records = WaterMeter::whereDate('date', $date)
            ->get()->sortBy('time_record', SORT_NATURAL)->values();
        //
        $round = 0;
        foreach ($records as $record) {
            if ($round == 0) {
                $flow_mm4 = CalculateController::getFlow($record->mm_4, ($last_yesterday_meter) ? $last_yesterday_meter->mm_4 : null);
                $flow_mm5 = CalculateController::getFlow($record->mm_5, ($last_yesterday_meter) ? $last_yesterday_meter->mm_5 : null);
                $flow_mm6 = CalculateController::getFlow($record->mm_6, ($last_yesterday_meter) ? $last_yesterday_meter->mm_6 : null);
            } else {
                $flow_mm4 = CalculateController::getFlow($record->mm_4, $records[$round - 1]->mm_4);
                $flow_mm5 = CalculateController::getFlow($record->mm_5, $records[$round - 1]->mm_5);
                $flow_mm6 = CalculateController::getFlow($record->mm_6, $records[$round - 1]->mm_6);
            }
            $record->flow = $flow_mm4 + $flow_mm5 + $flow_mm6;
            $record->flow_mm4=$flow_mm4;
            $record->flow_mm5=$flow_mm5;
            $record->flow_mm6=$flow_mm6;
            $round++;
//            $record->flow=number_format(($record->mm_4+$record->mm_5+$record->mm_6),2);
        }
        if ($records->count() > 0 && $last_yesterday_meter !=null) {
            $last_record = $records->last();
            $mm_4_used = CalculateController::getDailyUsed($last_record->mm_4, $last_yesterday_meter->mm_4);
            $mm_5_used = CalculateController::getDailyUsed($last_record->mm_5, $last_yesterday_meter->mm_5);
            $mm_6_used = CalculateController::getDailyUsed($last_record->mm_6, $last_yesterday_meter->mm_6);
        }

if ($last_yesterday_meter != null) {
    $last_yesterday_meter->zero_time_record = '0:00';
    //set yesterday flow null
//            $last_yesterday_meter->flow=number_format(($last_yesterday_meter->mm_4+$last_yesterday_meter->mm_5+$last_yesterday_meter->mm_6),2);
}
$results = collect([
    'data' => $records,
    'yesterday_meter' => $last_yesterday_meter,
    'mm_4_used' => $mm_4_used,
    'mm_5_used' => $mm_5_used,
    'mm_6_used' => $mm_6_used,
    'yesterday' => $yesterday,
    'date' => $date
]);
return response()->json($results);
}

//Add Record
public
function addRecord(Request $request)
{
    $result = WaterMeter::create([
        'date' => $request->input('date'),
        'time_record' => $request->input('time_record'),
        'real_time_record' => $request->input('real_time_record'),
        'mm_4' => $request->input('mm_4'),
        'mm_5' => $request->input('mm_5'),
        'mm_6' => $request->input('mm_6')
    ]);
    return response()->json($result);
}

//Update Record
public
function updateRecord(Request $request)
{
    $result = WaterMeter::where('id', $request->input('id'))
        ->update([
            'date' => $request->input('date'),
            'time_record' => $request->input('time_record'),
            'real_time_record' => $request->input('real_time_record'),
            'mm_4' => $request->input('mm_4'),
            'mm_5' => $request->input('mm_5'),
            'mm_6' => $request->input('mm_6')
        ]);
    return response()->json($result);
}

//Delete Record
public
function deleteRecord($id)
{
    $result = WaterMeter::where('id', $id)->delete();
    return response()->json($result);
}

//Get Monthly Results
public
static function getMonthlyResult($year, $month)
{
    $mm_4_used = 0;
    $mm_5_used = 0;
    $mm_6_used = 0;
    //Last Month
    $dateInput = Carbon::createFromFormat('Y-m-d', $year . '-' . $month . '-1');
//        Carbon::setTestNow($dateInput);
//        $last_month = Carbon::yesterday()->month;
//        $last_year = Carbon::yesterday()->year;
    $last_month = $dateInput->subDay(1)->month;
    $last_year = $dateInput->subDay(1)->year;
    $last_month_records = WaterMeter::whereYear('date', $last_year)
        ->whereMonth('date', $last_month)
        ->get()->sortBy('date', SORT_NATURAL)->values()->groupBy('date');
    //End Last Month
    //Get Current Month
    $records = WaterMeter::whereYear('date', $year)
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
                    $mm_4_used = CalculateController::getDailyUsed($result['last_record']['mm_4'], $last_month_records['mm_4']);
                    $mm_5_used = CalculateController::getDailyUsed($result['last_record']['mm_5'], $last_month_records['mm_5']);
                    $mm_6_used = CalculateController::getDailyUsed($result['last_record']['mm_6'], $last_month_records['mm_6']);
                }
            } else {
                $mm_4_used = CalculateController::getDailyUsed($results[$i]['last_record']['mm_4'], $results[$i - 1]['last_record']['mm_4']);
                $mm_5_used = CalculateController::getDailyUsed($results[$i]['last_record']['mm_5'], $results[$i - 1]['last_record']['mm_5']);
                $mm_6_used = CalculateController::getDailyUsed($results[$i]['last_record']['mm_6'], $results[$i - 1]['last_record']['mm_6']);

            }
            $result->put('used', [
                'mm_4_used' => $mm_4_used,
                'mm_5_used' => $mm_5_used,
                'mm_6_used' => $mm_6_used
            ]);
            $i++;
        }
    }
    $results = collect([
        'data' => $results,
        'init' => [
            'mm_4_used' => 0,
            'mm_5_used' => 0,
            'mm_6_used' => 0
        ]
    ]);
    return $results;
}

}
