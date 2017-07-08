<?php

namespace App\Http\Controllers\Api\Production;

use App\Models\Production\ProductionDate;
use App\Models\Production\ProductionDateTime;
use App\Models\Production\ProductionWork;
use App\Models\Production\ProductionWorkPerformance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class WorkController extends Controller
{
    /*Add new Work*/
    public function store(Request $request)
    {

        $result = DB::transaction(function () use ($request) {
            /*Production Date*/
            $productionDate = ProductionDate::where('date', $request->input('date'))->first();
            if ($productionDate == null) {
                $productionDate = new ProductionDate();
                $productionDate->date = $request->input('date');
                $productionDate->save();
            }
            /*Production Date Time*/
            $productionDateTime = ProductionDateTime::
            where('date_id', $productionDate->id)
                ->where('time_period', $request->input('time_period'))->first();
            if ($productionDateTime == null) {
                $productionDateTime = new ProductionDateTime();
                $productionDateTime->date_id = $productionDate->id;
                $productionDateTime->time_period = $request->input('time_period');
                $productionDateTime->save();
            }

            /*Production Work*/
            $productionWork = ProductionWork::where('p_date_time_id', $productionDateTime->id)
                ->where('p_activity_id', $request->input('activity_id'))
                ->where('p_shrimp_type_id', $request->input('shrimp_type_id'))
                ->where('p_shrimp_size_id', $request->input('shrimp_size_id'))
                ->first();
            if ($productionWork == null) {
                $productionWork = new ProductionWork();
                $productionWork->p_date_time_id = $productionDateTime->id;
                $productionWork->p_activity_id = $request->input('activity_id');
                $productionWork->p_shrimp_type_id = $request->input('shrimp_type_id');
                $productionWork->p_shrimp_size_id = $request->input('shrimp_size_id');
                $productionWork->save();
            }
            /*Production Work Performance*/
            $productionWorkPerformance = new ProductionWorkPerformance();
            $productionWorkPerformance->p_work_id = $productionWork->id;
            $productionWorkPerformance->em_id = $request->input('em_id');
            $productionWorkPerformance->weight = $request->input('weight');
            $productionWorkPerformance->created_by_user_id = $request->input('user_id');
            $productionWorkPerformance->updated_by_user_id = $request->input('user_id');
            $productionWorkPerformance->save();
        });
        /*End Transaction*/
        return response()->json($result);
    }

    /*Get Employee's amount weight*/
    public function employeeAmountWeight($id, $workID)
    {
        $amountWeight = ProductionWorkPerformance::where('p_work_id', $workID)
            ->where('em_id', $id)
            ->sum('weight');
        $round = ProductionWorkPerformance::where('p_work_id', $id)
            ->where('em_id', $id)
            ->count();
        $result = [
            'amountWeight' => $amountWeight,
            'round' => $round
        ];
        return response()->json($result);
    }
    /*Get Time Periods*/
    public function getTimePeriod($date){
        $timePeriod=ProductionDate::with('productionDateTime')
            ->where('date',$date)->first();
        return response()->json($timePeriod);
    }

    /*Get Work List*/
    public function getWorkList($time_period_id){
        $amountWeight=[];
        $averageWeight=[];
        $works=ProductionDateTime::with(
            'productionWork.productionActivity',
            'productionWork.productionShrimpSize',
            'productionWork.productionShrimpType',
            'productionWork.productionWorkPerformance'
        )->where('id',$time_period_id)->first();
//            dd($works);
        foreach ($works->productionWork as $work){
            $amountWeight=$work->productionWorkPerformance->sum('weight');
            $averageWeight=$work->productionWorkPerformance->avg('weight');
            $work->amountWeight=number_format($amountWeight,2);
            $work->averageWeight=number_format($averageWeight,2);
        }
        return response()->json($works);
    }

    /*Get Work Details*/
    public function getWorkDetails($work_id){
        $workDetailsList=ProductionWorkPerformance::where('p_work_id',$work_id)
            ->orderBy('updated_at')->get()->groupBy('em_id');
        return response($workDetailsList);
    }
    /*Delete Employee Weight*/
    public function deleteWeight($weight_id){
        $result=ProductionWorkPerformance::destroy($weight_id);
        return response($result);
    }
}
