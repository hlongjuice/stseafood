<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\WaterCooler;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DateTime;
use Carbon\Carbon;

class WaterCoolerController extends Controller
{
    //Get Record
    public function getRecordByDate($date)
    {
        $ripple_m_13_used = 0;
        $pre_tank_mm_8_used = 0;
        $dateInput = Carbon::createFromFormat('Y-m-d', $date);
//        Carbon::setTestNow($dateInput);
//        $yesterday = Carbon::yesterday()->toDateString();
        $yesterday=$dateInput->subDay(1)->toDateString();
        $last_yesterday_meter = WaterCooler::whereDate('date', $yesterday)
            ->get()
            ->sortBy('time_record', SORT_NATURAL)->values()->last();
        if ($last_yesterday_meter != null) {
            $last_yesterday_meter->zero_time_record = '0:00';
        }

        $records = WaterCooler::whereDate('date', $date)
            ->get()->sortBy('time_record', SORT_NATURAL)->values();
        $i = 0;
        foreach ($records as $record) {
            if ($i == 0) {
                $record->ripple_flow = CalculateController::
                getFlow($record->ripple_m_13, ($last_yesterday_meter) ? $last_yesterday_meter->ripple_m_13 : null);
            } else {
                $record->ripple_flow = CalculateController::getFlow($record->ripple_m_13, $records[$i - 1]->ripple_m_13);
            }
            $i++;
        }
        if ($records->count() > 0 && $last_yesterday_meter != null) {
            $last_record = $records->last();
            $ripple_m_13_used = CalculateController::getDailyUsed($last_record->ripple_m_13, $last_yesterday_meter->ripple_m_13);
            $pre_tank_mm_8_used = CalculateController::getDailyUsed($last_record->pre_tank_mm_8, $last_yesterday_meter->pre_tank_mm_8);
        }
        $results = collect([
            'data' => $records,
            'ripple_m_13_used' => $ripple_m_13_used,
            'pre_tank_mm_8_used' => $pre_tank_mm_8_used,
            'yesterday'=>$yesterday,
            'yesterday_meter'=>$last_yesterday_meter,
            'date'=>$date
        ]);
        return response()->json($results);
    }

    //Add Record
    public function addRecord(Request $request)
    {
        $result = WaterCooler::create([
            'date' => $request->input('date'),
            'time_record' => $request->input('time_record'),
            'real_time_record' => $request->input('real_time_record'),
            'ripple_temp' => $request->input('ripple_temp'),
            'ripple_m_13' => $request->input('ripple_m_13'),
            'chilled_tank' => $request->input('chilled_tank'),
            'pre_tank_temp' => $request->input('pre_tank_temp'),
            'pre_tank_mm_8' => $request->input('pre_tank_mm_8'),
            'pre_tank_pump' => $request->input('pre_tank_pump')
        ]);
        return response()->json($result);
    }

    //Update Record
    public function updateRecord(Request $request)
    {
        $result = WaterCooler::where('id', $request->input('id'))
            ->update([
                'date' => $request->input('date'),
                'time_record' => $request->input('time_record'),
                'real_time_record' => $request->input('real_time_record'),
                'ripple_temp' => $request->input('ripple_temp'),
                'ripple_m_13' => $request->input('ripple_m_13'),
                'chilled_tank' => $request->input('chilled_tank'),
                'pre_tank_temp' => $request->input('pre_tank_temp'),
                'pre_tank_mm_8' => $request->input('pre_tank_mm_8'),
                'pre_tank_pump' => $request->input('pre_tank_pump')
            ]);
        return response()->json($result);
    }

    //Delete Record
    public function deleteRecord($id)
    {
        $result = WaterCooler::where('id', $id)->delete();
        return response()->json($result);
    }

    //Get Monthly Results
    public static function getMonthlyResult($year, $month)
    {
        $ripple_m13_used = 0;
        $pre_tank_mm8_used = 0;
        //Last Month
        $dateInput = Carbon::createFromFormat('Y-m-d', $year . '-' . $month . '-1');
//        Carbon::setTestNow($dateInput);
//        $last_month = Carbon::yesterday()->month;
//        $last_year = Carbon::yesterday()->year;
        $last_month=$dateInput->subDay(1)->month;
        $last_year=$dateInput->subDay(1)->year;
        $last_month_records = WaterCooler::whereYear('date', $last_year)
            ->whereMonth('date', $last_month)
            ->get()->sortBy('date', SORT_NATURAL)->values()->groupBy('date');
        //End Last Month
        //Get Current Month
        $records = WaterCooler::whereYear('date', $year)
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
                        $ripple_m13_used = CalculateController::getDailyUsed($result['last_record']['ripple_m_13'], $last_month_records['ripple_m_13']);
                        $pre_tank_mm8_used = CalculateController::getDailyUsed($result['last_record']['pre_tank_mm_8'], $last_month_records['pre_tank_mm_8']);
                    }
                } else {
                    $ripple_m13_used = CalculateController::getDailyUsed($results[$i]['last_record']['ripple_m_13'], $results[$i - 1]['last_record']['ripple_m_13']);
                    $pre_tank_mm8_used = CalculateController::getDailyUsed($results[$i]['last_record']['pre_tank_mm_8'], $results[$i - 1]['last_record']['pre_tank_mm_8']);

                }
                $result->put('used', [
                    'ripple_m13_used' => $ripple_m13_used,
                    'pre_tank_mm8_used' => $pre_tank_mm8_used
                ]);
                $i++;
            }
        }
        $results=collect([
           'data'=>$results,
            'init'=>[
                'ripple_m13_used'=>0,
                'pre_tank_mm8_used'=>0
            ]
        ]);
        return $results;
    }

}
