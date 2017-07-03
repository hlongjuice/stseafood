<?php

namespace App\Http\Controllers\Api;

use App\Models\Production\ProductionWork;
use App\Models\Production\ProductionWorkPerformance;
use DB;
use App\Models\Production\ProductionDate;
use App\Models\Production\ProductionDateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productionSchedule = ProductionDate::
//        $productionSchedule=DB::table('production_date')
//            ->select('date')->groupBy('date')->get();
        with('productionDateTime.productionEmPerformance.employee')->get();

        return response()->json($productionSchedule);
    }

    public function getAllDate()
    {
        $scheduleDate=ProductionDate::all();
        return response()->json($scheduleDate);
    }

    public function getProductionSchedule($dateID){
        $productionSchedule=ProductionDate::
        with('productionDateTime.productionEmPerformance.employee')
            ->where('id',$dateID)
            ->get();
        return response()->json($productionSchedule);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
            ->where('time_period', $request->input('timePeriod'))->first();
        if ($productionDateTime == null) {
            $productionDateTime = new ProductionDateTime();
            $productionDateTime->date_id = $productionDate->id;
            $productionDateTime->time_period = $request->input('timePeriod');
            $productionDateTime->save();
        }

        /*Production Work*/
        $productionWork=ProductionWork::where('date_time_id',$productionDateTime)
            ->where('activity_id',$request->input('activity_id'))
            ->where('shrimp_type_id',$request->input('shrimp_type_id'))
            ->where('shrimp_size_id',$request->input('shrimp_size_id'))
            ->first();
        if($productionWork == null){
            $productionWork=new ProductionWork();
            $productionWork->p_date_time_id=$productionDateTime->id;
            $productionWork->p_activity_id=$request->$request->input('activity_id');
            $productionWork->p_shrimp_type_id=$request->input('shrimp_type_id');
            $productionWork->p_shrimp_size_id=$request->input('shrimp_size_id');
            $productionWork->save();
        }
        /*Production Work Performance*/
        $productionWorkPerformance=new ProductionWorkPerformance();
        $productionWorkPerformance->p_work_id=$productionWork->id;
        $productionWorkPerformance->em_id=$request->input('em_id');
        $productionWorkPerformance->weight=$request->input('weight');
        $productionWorkPerformance->save();



        return response()->json([
            'productionDate' => $productionDate,
            'productionDateTime' => $productionDateTime,
            'productionWork'=>$productionWork,
            'productionWorkPerformance'=>$productionWorkPerformance
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
