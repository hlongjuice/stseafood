<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\WaterSupplyOutSide;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WaterSupplyOutSideController extends Controller
{

    //Get Supply By Date
    public function getSupplyByDate($date)
    {
        $m_pwa_used = 0;
        $m_15_used = 0;
        $m_17_used = 0;
        $m_18_used = 0;
        $m_19_used = 0;
        $m_20_used = 0;
        $m_21_used = 0;
        $dateInput = Carbon::createFromFormat('Y-m-d', $date);
//        Carbon::setTestNow($dateInput);
//        $yesterday = Carbon::yesterday()->toDateString();
        $yesterday=$dateInput->subDay(1)->toDateString();
        $last_yesterday_meter = WaterSupplyOutSide::whereDate('date', $yesterday)
            ->get()
            ->sortBy('time_record', SORT_NATURAL)->values()->last();
        $supplies = WaterSupplyOutSide::whereDate('date', $date)
            ->get()->sortBy('time_record', SORT_NATURAL)->values();

        if ($supplies->count() > 0 && $last_yesterday_meter != null) {
            $last_supply = $supplies->last();
            $m_pwa_used = CalculateController::getDailyUsed($last_supply->m_pwa, $last_yesterday_meter->m_pwa);
            $m_15_used = CalculateController::getDailyUsed($last_supply->m_15, $last_yesterday_meter->m_15);
            $m_17_used = CalculateController::getDailyUsed($last_supply->m_17, $last_yesterday_meter->m_17);
            $m_18_used = CalculateController::getDailyUsed($last_supply->m_18, $last_yesterday_meter->m_18);
            $m_19_used = CalculateController::getDailyUsed($last_supply->m_19, $last_yesterday_meter->m_19);
            $m_20_used = CalculateController::getDailyUsed($last_supply->m_20, $last_yesterday_meter->m_20);
            $m_21_used = CalculateController::getDailyUsed($last_supply->m_21, $last_yesterday_meter->m_21);
        }

        $recorders = collect([
            'data' => $supplies,
            'm_pwa_used' => $m_pwa_used,
            'm_15_used' => $m_15_used,
            'm_17_used' => $m_17_used,
            'm_18_used' => $m_18_used,
            'm_19_used' => $m_19_used,
            'm_20_used' => $m_20_used,
            'm_21_used' => $m_21_used,
            'last' => $last_yesterday_meter,
            'yesterday'=>$yesterday,
            'yesterday_meter'=>$last_yesterday_meter,
            'date'=>$date
        ]);
        return response()->json($recorders);
    }

    //Get Supply By Month
    public function getSupplyByMonth(Request $request)
    {
        $supplies = WaterSupplyOutSide::whereYear('date', $request->input('year'))
            ->whereMonth('date', $request->input('month'))
            ->get();
        return response()->json($supplies);
    }

    //Add Supply
    public function addSupply(Request $request)
    {
        $result = WaterSupplyOutSide::create([
            'date' => $request->input('date'),
            'time_record' => $request->input('time_record'),
            'real_time_record' => $request->input('real_time_record'),
            'm_pwa' => $request->input('m_pwa'),
            'm_15' => $request->input('m_15'),
            'm_17' => $request->input('m_17'),
            'm_18' => $request->input('m_18'),
            'm_19' => $request->input('m_19'),
            'm_20' => $request->input('m_20'),
            'm_21' => $request->input('m_21')
        ]);
        return response()->json($result);
    }

    //Update Supply
    public function updateSupply(Request $request)
    {
        $result = WaterSupplyOutSide::where('id', $request->input('id'))
            ->update([
                'date' => $request->input('date'),
                'time_record' => $request->input('time_record'),
                'real_time_record' => $request->input('real_time_record'),
                'm_pwa' => $request->input('m_pwa'),
                'm_15' => $request->input('m_15'),
                'm_17' => $request->input('m_17'),
                'm_18' => $request->input('m_18'),
                'm_19' => $request->input('m_19'),
                'm_20' => $request->input('m_20'),
                'm_21' => $request->input('m_21')
            ]);
        return response()->json($result);
    }

    //Delete Supply
    public function deleteSupply($id)
    {
        $result = WaterSupplyOutSide::where('id', $id)->delete();
        return response()->json($result);
    }

    //Get Monthly Results
    public static function getMonthlyResult($year, $month)
    {
        $m_pwa_used = 0;
        $m_15_used = 0;
        $m_17_used = 0;
        $m_18_used = 0;
        $m_19_used = 0;
        $m_20_used = 0;
        $m_21_used = 0;
        //Last Month
        $dateInput = Carbon::createFromFormat('Y-m-d', $year . '-' . $month . '-1');
//        Carbon::setTestNow($dateInput);
//        $last_month = Carbon::yesterday()->month;
//        $last_year = Carbon::yesterday()->year;
        $last_month=$dateInput->subDay(1)->month;
        $last_year=$dateInput->subDay(1)->year;
        $last_month_records = WaterSupplyOutSide::whereYear('date', $last_year)
            ->whereMonth('date', $last_month)
            ->get()->sortBy('date', SORT_NATURAL)->values()->groupBy('date');
        //End Last Month
        //Get Current Month
        $records = WaterSupplyOutSide::whereYear('date', $year)
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
                        $m_pwa_used = CalculateController::getDailyUsed($result['last_record']['m_pwa'], $last_month_records['m_pwa']);
                        $m_15_used = CalculateController::getDailyUsed($result['last_record']['m_15'], $last_month_records['m_15']);
                        $m_17_used = CalculateController::getDailyUsed($result['last_record']['m_17'], $last_month_records['m_17']);
                        $m_18_used = CalculateController::getDailyUsed($result['last_record']['m_18'], $last_month_records['m_18']);
                        $m_19_used = CalculateController::getDailyUsed($result['last_record']['m_19'], $last_month_records['m_19']);
                        $m_20_used = CalculateController::getDailyUsed($result['last_record']['m_20'], $last_month_records['m_20']);
                        $m_21_used = CalculateController::getDailyUsed($result['last_record']['m_21'], $last_month_records['m_21']);

                    }
                } else {
                    $m_pwa_used = CalculateController::getDailyUsed($results[$i]['last_record']['m_pwa'], $results[$i - 1]['last_record']['m_pwa']);
                    $m_15_used = CalculateController::getDailyUsed($results[$i]['last_record']['m_15'], $results[$i - 1]['last_record']['m_15']);
                    $m_17_used = CalculateController::getDailyUsed($results[$i]['last_record']['m_17'], $results[$i - 1]['last_record']['m_17']);
                    $m_18_used = CalculateController::getDailyUsed($results[$i]['last_record']['m_18'], $results[$i - 1]['last_record']['m_18']);
                    $m_19_used = CalculateController::getDailyUsed($results[$i]['last_record']['m_19'], $results[$i - 1]['last_record']['m_19']);
                    $m_20_used = CalculateController::getDailyUsed($results[$i]['last_record']['m_20'], $results[$i - 1]['last_record']['m_20']);
                    $m_21_used = CalculateController::getDailyUsed($results[$i]['last_record']['m_21'], $results[$i - 1]['last_record']['m_21']);

                }
                $result->put('used', [
                    'm_pwa_used' => $m_pwa_used,
                    'm_15_used' => $m_15_used,
                    'm_17_used' => $m_17_used,
                    'm_18_used' => $m_18_used,
                    'm_19_used' => $m_19_used,
                    'm_20_used' => $m_20_used,
                    'm_21_used' => $m_21_used
                ]);
                $i++;
            }
        }
        $results = collect([
            'data' => $results,
            'init' => [
                'm_pwa_used' => 0,
                'm_15_used' => 0,
                'm_17_used' => 0,
                'm_18_used' => 0,
                'm_19_used' => 0,
                'm_20_used' => 0,
                'm_21_used' => 0
            ]
        ]);
        return $results;
    }

}
