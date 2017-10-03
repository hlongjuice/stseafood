<?php

namespace App\Http\Controllers\Api\Eng\Result;

use App\Http\Controllers\Api\Eng\BoilerController;
use App\Http\Controllers\Api\Eng\CondensController;
use App\Http\Controllers\Api\Eng\IceMakerController;
use App\Http\Controllers\Api\Eng\Tank210Controller;
use App\Http\Controllers\Api\Eng\WaterCoolerController;
use App\Http\Controllers\Api\Eng\WaterFiltrationController;
use App\Http\Controllers\Api\Eng\WaterMeterController;
use App\Http\Controllers\Api\Eng\WaterSupplyOutSideController;
use App\Http\Controllers\WebService\DateController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WaterUsageResultController extends Controller
{
    public static function getResult(Request $request){
       $waterUsage=new WaterUsageResultController();
        return $waterUsage->getResultByMonth($request);
    }
    public function getResultByMonth(Request $request)
    {
        $month=$request->input('month');
        $year=$request->input('year');
        $th_months = DateController::getFullThaiMonths();
        $thai_month = "";
        foreach ($th_months as $key => $value) {
            if ($month == $key + 1) {
                $thai_month = $value;
            }
        }
        $results=$this->calculateResult($month,$year);
        $results->put('thai_month',$thai_month);
        $results->put('year',$year);
        return response()->json($results);
    }
    
    public function calculateResult($month,$year){
        /*Initial*/
        $dateInput = Carbon::createFromFormat('Y-m-d', $year . '-' . $month . '-1');
//        Carbon::setTestNow($dateInput);
//        $totalDay = Carbon::today()->daysInMonth;
//        $dateString = Carbon::today()->toDateString();
        $totalDay=$dateInput->daysInMonth;
        $dateString=$dateInput->toDateString();

        //All Used
        $records = collect([
            'ws_outside' => WaterSupplyOutSideController::getMonthlyResult($year, $month),
            'water_filtration' => WaterFiltrationController::getMonthlyResult($year, $month),
            'condens' => CondensController::getMonthlyResult($year, $month),
            'boiler' => BoilerController::getMonthlyResult($year, $month),
            'tank_210' => Tank210Controller::getMonthlyResult($year, $month),
            'water_meter' => WaterMeterController::getMonthlyResult($year, $month),
            'ice_maker' => IceMakerController::getMonthlyResult($year, $month),
            'water_cooler' => WaterCoolerController::getMonthlyResult($year, $month),
            'day' => $totalDay,
            'dateString' => $dateString
        ]);

        $allDate = collect([]);
        $dateNow=$dateString;
        for ($day = 1; $day <= $totalDay; $day++) {
            $ws_outside = $records['ws_outside'];
            $water_filtration = $records['water_filtration'];
            $condens = $records['condens'];
            $boiler = $records['boiler'];
            $tank_210 = $records['tank_210'];
            $water_meter = $records['water_meter'];
            $ice_maker = $records['ice_maker'];
            $water_cooler = $records['water_cooler'];
            //Set Date`
            if ($day != 1) {
                $dateNow = Carbon::createFromFormat('Y-m-d', $year . '-' . $month . '-' . $day)->toDateString();
//                Carbon::setTestNow($nextDate);
            }
            $usedData = collect([
                'date' => $dateNow,
                'used' => collect([
                    'ws_outside' => $ws_outside['init'],
                    'water_filtration' => $water_filtration['init'],
                    'condens' => $condens['init'],
                    'boiler' => $boiler['init'],
                    'tank_210' => $tank_210['init'],
                    'water_meter' => $water_meter['init'],
                    'ice_maker' => $ice_maker['init'],
                    'water_cooler' => $water_cooler['init'],
                ])
            ]);
            //Ws Outside
            for ($i = 0; $i < $ws_outside['data']->count(); $i++) {
                if ($ws_outside['data'][$i]['date'] == $dateNow) {
                    $usedData['used']['ws_outside'] = $ws_outside['data'][$i]['used'];
                }
            }
            //Water Filtration
            for ($i = 0; $i < $water_filtration['data']->count(); $i++) {
                if ($water_filtration['data'][$i]['date'] == $dateNow) {
                    $usedData['used']['water_filtration'] = $water_filtration['data'][$i]['used'];
                }
            }
            //Condens
            for ($i = 0; $i < $condens['data']->count(); $i++) {
                if ($condens['data'][$i]['date'] == $dateNow) {
                    $usedData['used']['condens'] = $condens['data'][$i]['used'];
                }
            }
            //Boiler
            for ($i = 0; $i < $boiler['data']->count(); $i++) {
                if ($boiler['data'][$i]['date'] == $dateNow) {
                    $usedData['used']['boiler'] = $boiler['data'][$i]['used'];
                }
            }
            //Tank 210
            for ($i = 0; $i < $tank_210['data']->count(); $i++) {
                if ($tank_210['data'][$i]['date'] == $dateNow) {
                    $usedData['used']['tank_210'] = $tank_210['data'][$i]['used'];
                }
            }
            //Water Meter
            for ($i = 0; $i < $water_meter['data']->count(); $i++) {
                if ($water_meter['data'][$i]['date'] == $dateNow) {
                    $usedData['used']['water_meter'] = $water_meter['data'][$i]['used'];
                }
            }
            //Ice Maker
            for ($i = 0; $i < $ice_maker['data']->count(); $i++) {
                if ($ice_maker['data'][$i]['date'] == $dateNow) {
                    $usedData['used']['ice_maker'] = $ice_maker['data'][$i]['used'];
                }
            }
            //Water Cooler
            for ($i = 0; $i < $water_cooler['data']->count(); $i++) {
                if ($water_cooler['data'][$i]['date'] == $dateNow) {
                    $usedData['used']['water_cooler'] = $water_cooler['data'][$i]['used'];
                }
            }
            $allDate->push($usedData);
        }
        $records->put('allDate', $allDate);
        $sumUsedResult = $this->sumUsed($records['allDate']);
        $results = $this->sumColumn($sumUsedResult);

        $avgSumUsed=collect([]);
        $avgUsed=collect([]);
        foreach($results['sumRow']['sumUsed'] as $keys=>$value){
            $avg=number_format($value/$totalDay,2);
            $avgSumUsed->put($keys,$avg);
        }
        foreach ($results['sumRow']['used'] as $keys=>$value){
            $avg=number_format($value/$totalDay,2);
            $avgUsed->put($keys,$avg);
        }
        $results->put('avgRow',[
            'sumUsed'=>$avgSumUsed,
            'used'=>$avgUsed
        ]);

        return $results;
    }

    /*Sum Used*/
    public function sumUsed($allDate)
    {
        foreach ($allDate as $date) {
            $used = $date['used'];
            $sumP1P2 = $used['water_filtration']['p1_mm1_used'] + $used['water_filtration']['p2_mm2_used'];
            $sumP1P2Minus210 = $sumP1P2 - (float)$used['tank_210']['mm3_used'];
            $sumCondens = $used['condens']['con2_w_meter_used'] + $used['condens']['con3_w_meter_used'] + $used['condens']['con5_meter_m5_used']
                + $used['condens']['con6_meter_m6_used'] + $used['condens']['con7_meter_m7_used'] + $used['condens']['con8_w_meter_used'];
            $sumB1B2 = $used['boiler']['boiler1_meter_used'] + $used['boiler']['boiler2_meter_used'];
            $sumMain3 = $used['water_meter']['mm_4_used'] + $used['water_meter']['mm_5_used'] + $used['water_meter']['mm_6_used'];
            $mm6MinusCondBoiler = $used['water_meter']['mm_6_used'] - $sumCondens - $sumB1B2;
            $sumIce = $used['ice_maker']['freezer1_m12_used'] + $used['ice_maker']['freezer2_m2_used'] + $used['ice_maker']['freezer3_m14_used'];
            $sumBoilerMain3M21 = $sumB1B2 + $sumMain3 + $used['ws_outside']['m_21_used'];
            $sumM1517181920 = $used['ws_outside']['m_15_used'] + $used['ws_outside']['m_17_used'] + $used['ws_outside']['m_18_used']
                + $used['ws_outside']['m_19_used'] + $used['ws_outside']['m_20_used'];
            $sumTotal = $sumMain3+$sumM1517181920+$used['ws_outside']['m_21_used']+$used['ws_outside']['m_pwa_used'];
            $date->put('sumUsed', [
                'sumP1P2' => $sumP1P2,
                'sumP1P2Minus210' => $sumP1P2Minus210,
                'sumCondens' => $sumCondens,
                'sumB1B2' => $sumB1B2,
                'sumWaterMeterMain3' => $sumMain3,
                'main3MinusCondBoiler' => $mm6MinusCondBoiler,
                'sumIce' => $sumIce,
                'sumBoilerMain3M21' => $sumBoilerMain3M21,
                'sumTotal' => $sumTotal
            ]);
        }
        return $allDate;
    }

    /*Sum Column*/
    public function sumColumn($allDate)
    {
        $allKeys = $this->getAllKeys($allDate);
        $allUsedKeys = $allKeys['allUsedKeys'];
        $allSumUsedKeys = $allKeys['allSumUsedKeys'];

        //Sum Used Meter
        $usedColumn = collect([]);
        foreach ($allUsedKeys as $key) {
            foreach ($key['child'] as $cKey) {
                $sumUsed = $allDate->sum(function ($date) use ($key, $cKey) {
                    $pKey = $key['parent'];
                    return $date['used'][$pKey][$cKey];
                });
                $usedColumn->put($cKey, $sumUsed);
            }
        }
        //Sum Compound Used Meter
        $compoundColumn = collect([]);
        foreach ($allSumUsedKeys as $comKey) {
            $sumUsed = $allDate->sum(function ($date) use ($comKey) {
                return $date['sumUsed'][$comKey];
            });
            $compoundColumn->put($comKey, $sumUsed);
        }


        $results=collect([
            'data'=>$allDate,
            'sumRow'=>[
                'used' => $usedColumn,
                'sumUsed' => $compoundColumn
            ]
        ]);
        // $allDate->put('test',$usedColumn);

        return $results;
    }

    public function getAllKeys($allDate)
    {
        $allKeys = collect([]);
        $lv1Keys = $allDate[0]['used']->keys();//'used' Parent Keys has Many Child
        $lv2Keys = [];
        $allUsedKeys = collect([]);
        foreach ($lv1Keys as $lv1Key) {
            $lv2Keys = array_keys($allDate[0]['used'][$lv1Key]);//Child Keys
            $temp = collect([
                'parent' => $lv1Key,
                'child' => $lv2Keys
            ]);
            $allUsedKeys->push($temp);
        }

        //Compound Used Keys
        $allSumUsedKeys = array_keys($allDate[0]['sumUsed']);
        $allKeys->put('allSumUsedKeys', $allSumUsedKeys);
        $allKeys->put('allUsedKeys', $allUsedKeys);

        return $allKeys;
    }
}
