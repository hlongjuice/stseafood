<?php

namespace App\Http\Controllers\Api\QC;

use App\Models\QC\QcSupplierReceiving;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RecorderResultController extends Controller
{
    public function getDailyResult($date)
    {
        $last_five_shrimp_dead = 0;
        $shrimp_dead = 0;
        $total_shrimp_weight = 0;
        $last_five_status = 0;
        $last_five_records = null;
        $recorders = QcSupplierReceiving::with('shrimpReceiving.waterTemp', 'supplier')
            ->where('date', $date)->get();
        foreach ($recorders as $recorder) {
            $recorder->total_shrimp_weight = $recorder->shrimpReceiving->sum('weight');
            /*If Have Last Five Status*/
            if ($recorder->last_five_round_status == 1) {
                $last_five_records = $recorder->shrimpReceiving->splice(-5);
                $recorder->last_five_shrimp_dead = $last_five_records->sum('real_shrimp_dead');
            }
            $recorder->shrimp_dead = $recorder->shrimpReceiving->sum('real_shrimp_dead');
        }

        return response()->json($recorders);
    }

    public function getMonthlyResult(Request $request)
    {
        $recorders = QcSupplierReceiving::with('shrimpReceiving.waterTemp', 'supplier')
            ->whereYear('date', $request->input('year'))
            ->whereMonth('date', $request->input('month'))
            ->orderBy('date', 'asc')
            ->get();
        foreach ($recorders as $recorder) {
            //Initial Data
            $last_five_shrimp_dead = 0;
            $last_five_shrimp_dead_percent = 0;
            $shrimp_dead = 0;
            $shrimp_dead_percent = 0;
            $total_shrimp_dead = 0;
            $total_shrimp_dead_percent = 0;
            $total_shrimp_weight = 0;
            $last_five_status = 0;
            $last_five_records = null;
            //End Initial
            //Calculate
            $total_shrimp_weight = $recorder->shrimpReceiving->sum('weight');
            $total_shrimp_dead = $recorder->shrimpReceiving->sum('real_shrimp_dead');
            /*If Have Last Five Status*/
            if ($recorder->last_five_round_status == 1) {
                $last_five_records = $recorder->shrimpReceiving->splice(-5);
                $last_five_shrimp_dead = $last_five_records->sum('real_shrimp_dead');
            }
            $shrimp_dead = $recorder->shrimpReceiving->sum('real_shrimp_dead');
            if ($total_shrimp_weight > 0) {
                $total_shrimp_dead_percent = ($total_shrimp_dead / $total_shrimp_weight) * 100;
                $shrimp_dead_percent = ($shrimp_dead / $total_shrimp_weight) * 100;
                $last_five_shrimp_dead_percent = ($last_five_shrimp_dead / $total_shrimp_weight) * 100;
            }

            $recorder->real_shrimp_soft_percent=number_format(($recorder->real_shrimp_soft/$total_shrimp_weight)*100,2);
            $recorder->shrimp_dead = $shrimp_dead;
            $recorder->last_five_shrimp_dead = $last_five_shrimp_dead;
            $recorder->total_shrimp_dead = $total_shrimp_dead;
            $recorder->total_shrimp_weight = $total_shrimp_weight;
            $recorder->shrimp_dead_percent = number_format($shrimp_dead_percent, 2);
            $recorder->total_shrimp_dead_percent = number_format($total_shrimp_dead_percent, 2);
            $recorder->last_five_shrimp_dead_percent = number_format($last_five_shrimp_dead_percent, 2);
        }
        return response()->json($recorders);
    }

    public function getYearlyResult($year)
    {
        $year_start=$year-3;
        $year_end=$year;
//        abort(500,$year_start.'-'.$year_end);
        $months = QcSupplierReceiving::with('shrimpReceiving.waterTemp', 'supplier')
            ->whereYear('date', '>', $year_start)
            ->whereYear('date','<=',$year_end)
            ->orderBy('date', 'asc')
            ->get()
            ->groupBy(function ($item) {
                return Carbon::createFromFormat('Y-m-d', $item->date)->format('Y-m');
            });

        foreach ($months as $month) {
            foreach ($month as $recorder) {
                //Initial Data
                $last_five_shrimp_dead = 0;
                $last_five_shrimp_dead_percent = 0;
                $shrimp_dead = 0;
                $shrimp_dead_percent = 0;
                $total_shrimp_dead = 0;
                $total_shrimp_dead_percent = 0;
                $total_shrimp_weight = 0;
                $last_five_status = 0;
                $last_five_records = null;
                //End Initial
                //Calculate
                $total_shrimp_weight = $recorder->shrimpReceiving->sum('weight');
                $total_shrimp_dead = $recorder->shrimpReceiving->sum('real_shrimp_dead');
                /*If Have Last Five Status*/
                if ($recorder->last_five_round_status == 1) {
                    $last_five_records = $recorder->shrimpReceiving->splice(-5);
                    $last_five_shrimp_dead = $last_five_records->sum('real_shrimp_dead');
                }
                $shrimp_dead = $recorder->shrimpReceiving->sum('real_shrimp_dead');
                if ($total_shrimp_weight > 0) {
                    $total_shrimp_dead_percent = ($total_shrimp_dead / $total_shrimp_weight) * 100;
                    $shrimp_dead_percent = ($shrimp_dead / $total_shrimp_weight) * 100;
                    $last_five_shrimp_dead_percent = ($last_five_shrimp_dead / $total_shrimp_weight) * 100;
                }
                /*Daily Result*/
                $recorder->d_shrimp_dead = $shrimp_dead;
                $recorder->d_last_five_shrimp_dead = $last_five_shrimp_dead;
                $recorder->d_total_shrimp_dead = $total_shrimp_dead;
                $recorder->d_total_shrimp_weight = $total_shrimp_weight;
                $recorder->d_shrimp_dead_percent = number_format($shrimp_dead_percent, 2);
                $recorder->d_total_shrimp_dead_percent = number_format($total_shrimp_dead_percent, 2);
                $recorder->d_last_five_shrimp_dead_percent = number_format($last_five_shrimp_dead_percent, 2);
            }
            /*Monthly Result*/
            $month->put('m_total_shrimp_weight', $month->sum('d_total_shrimp_weight'));
            $month->put('m_shrimp_dead', $month->sum('d_shrimp_dead'));
            $month->put('m_shrimp_dead_percent', $month->sum('d_shrimp_dead_percent'));
            $month->put('m_last_five_shrimp_dead', $month->sum('d_last_five_shrimp_dead'));
            $month->put('m_total_shrimp_dead', $month->sum('d_total_shrimp_dead'));
            $month->put('m_total_shrimp_dead_percent', $month->sum('d_total_shrimp_dead_percent'));
            $month->put('m_last_five_shrimp_dead_percent', $month->sum('d_last_five_shrimp_dead_percent'));
            $month->put('month', (int)Carbon::createFromFormat('Y-m-d', $month[0]->date)->format('m'));
            $month->put('year', (int)Carbon::createFromFormat('Y-m-d', $month[0]->date)->format('Y'));
        }
//        $months->put('y_avg_total_shrimp_dead_percent', number_format($months->avg('m_total_shrimp_dead_percent'), 2));
        //Group By Years
        $years = $months->groupBy(function ($item, $keys) {
            return Carbon::createFromFormat('Y-m', $keys)->format('Y');
        });
//        $keys=$years->keys();
        $results = [];
        foreach ($years as $key=>$year) {
            $rebuild=collect([]);
            $rebuild->put('data',$year);
            $rebuild->put('year',$key);
            $results[] = $rebuild;
        }
//        return response()->json($results);
        return response()->json($results);
    }

    public function getSupplierResultByMonth(Request $request)
    {
        $recorders = QcSupplierReceiving::with('shrimpReceiving.waterTemp', 'supplier')
            ->where('supplier_id', $request->input('supplier_id'))
            ->whereYear('date', $request->input('year'))
            ->whereMonth('date', $request->input('month'))
            ->orderBy('date', 'asc')
            ->get();
        foreach ($recorders as $recorder) {
            //Initial Data
            $last_five_shrimp_dead = 0;
            $last_five_shrimp_dead_percent = 0;
            $shrimp_dead = 0;
            $shrimp_dead_percent = 0;
            $total_shrimp_dead = 0;
            $total_shrimp_dead_percent = 0;
            $total_shrimp_weight = 0;
            $last_five_status = 0;
            $last_five_records = null;
            //End Initial
            //Calculate
            $total_shrimp_weight = $recorder->shrimpReceiving->sum('weight');
            $total_shrimp_dead = $recorder->shrimpReceiving->sum('real_shrimp_dead');
            /*If Have Last Five Status*/
            if ($recorder->last_five_round_status == 1) {
                $last_five_records = $recorder->shrimpReceiving->splice(-5);
                $last_five_shrimp_dead = $last_five_records->sum('real_shrimp_dead');
            }
            $shrimp_dead = $recorder->shrimpReceiving->sum('real_shrimp_dead');
            if ($total_shrimp_weight > 0) {
                $total_shrimp_dead_percent = ($total_shrimp_dead / $total_shrimp_weight) * 100;
                $shrimp_dead_percent = ($shrimp_dead / $total_shrimp_weight) * 100;
                $last_five_shrimp_dead_percent = ($last_five_shrimp_dead / $total_shrimp_weight) * 100;
            }


            $recorder->real_shrimp_soft_percent=number_format(($recorder->real_shrimp_soft/$total_shrimp_weight)*100,2);
            $recorder->shrimp_dead = $shrimp_dead;
            $recorder->last_five_shrimp_dead = $last_five_shrimp_dead;
            $recorder->total_shrimp_dead = $total_shrimp_dead;
            $recorder->total_shrimp_weight = $total_shrimp_weight;
            $recorder->shrimp_dead_percent = number_format($shrimp_dead_percent, 2);
            $recorder->total_shrimp_dead_percent = number_format($total_shrimp_dead_percent, 2);
            $recorder->last_five_shrimp_dead_percent = number_format($last_five_shrimp_dead_percent, 2);
        }
        return response()->json($recorders);

    }

    public function getSupplierResultByYear(Request $request)
    {
        $recorders = QcSupplierReceiving::with('shrimpReceiving.waterTemp', 'supplier')
            ->where('supplier_id', $request->input('supplier_id'))
            ->whereYear('date', $request->input('year'))
            ->orderBy('date', 'asc')
            ->get();
        foreach ($recorders as $recorder) {
            //Initial Data
            $last_five_shrimp_dead = 0;
            $last_five_shrimp_dead_percent = 0;
            $shrimp_dead = 0;
            $shrimp_dead_percent = 0;
            $total_shrimp_dead = 0;
            $total_shrimp_dead_percent = 0;
            $total_shrimp_weight = 0;
            $last_five_status = 0;
            $last_five_records = null;
            //End Initial
            //Calculate
            $total_shrimp_weight = $recorder->shrimpReceiving->sum('weight');
            $total_shrimp_dead = $recorder->shrimpReceiving->sum('real_shrimp_dead');
            /*If Have Last Five Status*/
            if ($recorder->last_five_round_status == 1) {
                $last_five_records = $recorder->shrimpReceiving->splice(-5);
                $last_five_shrimp_dead = $last_five_records->sum('real_shrimp_dead');
            }
            $shrimp_dead = $recorder->shrimpReceiving->sum('real_shrimp_dead');
            if ($total_shrimp_weight > 0) {
                $total_shrimp_dead_percent = ($total_shrimp_dead / $total_shrimp_weight) * 100;
                $shrimp_dead_percent = ($shrimp_dead / $total_shrimp_weight) * 100;
                $last_five_shrimp_dead_percent = ($last_five_shrimp_dead / $total_shrimp_weight) * 100;
            }


            $recorder->real_shrimp_soft_percent=number_format(($recorder->real_shrimp_soft/$total_shrimp_weight)*100,2);
            $recorder->shrimp_dead = $shrimp_dead;
            $recorder->last_five_shrimp_dead = $last_five_shrimp_dead;
            $recorder->total_shrimp_dead = $total_shrimp_dead;
            $recorder->total_shrimp_weight = $total_shrimp_weight;
            $recorder->shrimp_dead_percent = number_format($shrimp_dead_percent, 2);
            $recorder->total_shrimp_dead_percent = number_format($total_shrimp_dead_percent, 2);
            $recorder->last_five_shrimp_dead_percent = number_format($last_five_shrimp_dead_percent, 2);
        }
        return response()->json($recorders);

    }

    public function getSupplierResultByQuarter(Request $request)
    {
     /*   $start_month = 0;
        $end_month=0;
        switch ((int)$request->input('quarter')){
            case '1':{
                $start_month=1;
                $end_month=3;
            }break;
            case '2':{
                $start_month=4;
                $end_month=6;
            }break;
            case '3':{
                $start_month=7;
                $end_month=9;
            }break;
            case '4':{
                $start_month=10;
                $end_month=12;
            }
        }*/
        $recorders = QcSupplierReceiving::with('shrimpReceiving.waterTemp', 'supplier')
            ->where('supplier_id', $request->input('supplier_id'))
            ->whereYear('date', $request->input('year'))
            ->whereMonth('date', '>=',$request->input('start_month'))
            ->whereMonth('date', '<=', $request->input('end_month'))
            ->orderBy('date', 'asc')
            ->get();
        foreach ($recorders as $recorder) {
            //Initial Data
            $last_five_shrimp_dead = 0;
            $last_five_shrimp_dead_percent = 0;
            $shrimp_dead = 0;
            $shrimp_dead_percent = 0;
            $total_shrimp_dead = 0;
            $total_shrimp_dead_percent = 0;
            $total_shrimp_weight = 0;
            $last_five_status = 0;
            $last_five_records = null;
            //End Initial
            //Calculate
            $total_shrimp_weight = $recorder->shrimpReceiving->sum('weight');
            $total_shrimp_dead = $recorder->shrimpReceiving->sum('real_shrimp_dead');
            /*If Have Last Five Status*/
            if ($recorder->last_five_round_status == 1) {
                $last_five_records = $recorder->shrimpReceiving->splice(-5);
                $last_five_shrimp_dead = $last_five_records->sum('real_shrimp_dead');
            }
            $shrimp_dead = $recorder->shrimpReceiving->sum('real_shrimp_dead');
            if ($total_shrimp_weight > 0) {
                $total_shrimp_dead_percent = ($total_shrimp_dead / $total_shrimp_weight) * 100;
                $shrimp_dead_percent = ($shrimp_dead / $total_shrimp_weight) * 100;
                $last_five_shrimp_dead_percent = ($last_five_shrimp_dead / $total_shrimp_weight) * 100;
            }


            $recorder->real_shrimp_soft_percent=number_format(($recorder->real_shrimp_soft/$total_shrimp_weight)*100,2);
            $recorder->shrimp_dead = $shrimp_dead;
            $recorder->last_five_shrimp_dead = $last_five_shrimp_dead;
            $recorder->total_shrimp_dead = $total_shrimp_dead;
            $recorder->total_shrimp_weight = $total_shrimp_weight;
            $recorder->shrimp_dead_percent = number_format($shrimp_dead_percent, 2);
            $recorder->total_shrimp_dead_percent = number_format($total_shrimp_dead_percent, 2);
            $recorder->last_five_shrimp_dead_percent = number_format($last_five_shrimp_dead_percent, 2);
        }
        return response()->json($recorders);
    }
}
