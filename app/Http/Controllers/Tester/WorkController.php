<?php

namespace App\Http\Controllers\Tester;

use App\Models\Production\ProductionDateTime;
use App\Models\Production\ProductionWorkPerformance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use function MongoDB\BSON\toJSON;

class WorkController extends Controller
{
    public function getWorkList(){
//        $amountWeight=0.00;
//        $averageWeight=0.00;
        $reult=[];
        $works=ProductionDateTime::with(
            'productionWork',
            'productionWork.productionActivity',
            'productionWork.productionShrimpSize',
            'productionWork.productionShrimpType',
            'productionWork.productionWorkPerformance'
        )->where('id',13)->first();
//        dd($works->productionWork);
        foreach ($works->productionWork as $work){
            $amountWeight=$work->productionWorkPerformance->sum('weight');
            $averageWeight=$work->productionWorkPerformance->avg('weight');
            $work->result= [
                'amountWeight'=>$amountWeight,
                'averageWeight'=>$averageWeight
            ];
        }
        dd($works->productionWork);
    }

    /*Get Work Details*/
    public function getWorkDetails($work_id){
        $workDetailsList=ProductionWorkPerformance::where('p_work_id',$work_id)
                ->get()->groupBy('em_id');

//        $amountWeight=ProductionWorkPerformance::where('p_work_id',$work_id)->get()->groupBy('em_id');
     /*   $amountWeight=DB::table('production_work_performance')
            ->where('p_work_id',$work_id)
            ->select(DB::raw('em_id,sum(weight)'))
            ->groupBy('em_id')
            ->get();*/

      dd($workDetailsList->toJson());
//
//        $employeeWorkDetails=[];
//        foreach ($workDetailsList as $workDetail){
//
//        }
//        return response()->json($workDetailsList);
    }
}
