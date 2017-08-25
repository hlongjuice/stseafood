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

    public function getYearlyResult(Request $request)
    {
        $months = QcSupplierReceiving::with('shrimpReceiving.waterTemp', 'supplier')
            ->whereYear('date', '>=', (int)$request->input('year') - 3)
            ->get()
            ->groupBy(function ($item) {
                return Carbon::createFromFormat('Y-m-d', $item->date)->format('Y-m');
            });
//        $years->put('years',$years->groupBy(function($item,$keys){
//            return Carbon::createFromFormat('Y-m', $keys)->format('Y');
//        }));

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
            $month->put('month',(int)Carbon::createFromFormat('Y-m-d',$month[0]->date)->format('m'));
        }
//        $months->put('y_avg_total_shrimp_dead_percent', number_format($months->avg('m_total_shrimp_dead_percent'), 2));
        $years=$months->groupBy(function($item,$keys){
            return Carbon::createFromFormat('Y-m', $keys)->format('Y');
        });
        $results=[];
        foreach ($years as $year){
//            $year->put('y_avg_total_shrimp_dead_percent',number_format($year->avg('m_total_shrimp_dead_percent'),2));
            $results[]=$year;
        }
        return response()->json($results);
    }
}
