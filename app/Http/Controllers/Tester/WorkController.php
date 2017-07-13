<?php

namespace App\Http\Controllers\Tester;

use App\Models\Production\ProductionDate;
use App\Models\Production\ProductionDateTime;
use App\Models\Production\ProductionWork;
use App\Models\Production\ProductionWorkPerformance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use function MongoDB\BSON\toJSON;

class WorkController extends Controller
{
    public function create(){
        return view('tester.add_work');
    }
    public function store(Request $request){
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
      dd($workDetailsList->toJson());
    }
    /*Add Work List*/
    public function addWork(Request $request){
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
    }
}
