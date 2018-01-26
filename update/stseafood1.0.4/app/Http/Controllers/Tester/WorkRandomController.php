<?php

namespace App\Http\Controllers\Tester;

use App\Models\Employee;
use App\Models\Production\ProductionActivity;
use App\Models\Production\ProductionDate;
use App\Models\Production\ProductionDateTime;
use App\Models\Production\ProductionEmployee;
use App\Models\Production\ProductionShrimpSize;
use App\Models\Production\ProductionShrimpType;
use App\Models\Production\ProductionWork;
use App\Models\Production\ProductionWorkPerformance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class WorkRandomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tester/add_work');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $shrimp_type=ProductionShrimpType::inRandomOrder()->first();
        $shrimp_size=ProductionShrimpSize::inRandomOrder()->first();
        $activity=ProductionActivity::inRandomOrder()->first();
        $random_employees=ProductionEmployee::where('group',$request->input('group'))->take(10)->get();

        for($i=0;$i<20;$i++) {
            $result = DB::transaction(function () use (
                $request,
                $shrimp_size,
                $shrimp_type,
                $activity,
                $random_employees
            ) {
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
                    ->where('p_activity_id', $activity->id)
                    ->where('p_shrimp_type_id', $shrimp_type->id)
                    ->where('p_shrimp_size_id', $shrimp_size->id)
                    ->first();
                if ($productionWork == null) {
                    /*Random Work*/
                    $productionWork = new ProductionWork();
                    $productionWork->p_date_time_id = $productionDateTime->id;
                    $productionWork->p_activity_id = $activity->id;
                    $productionWork->p_shrimp_type_id = $shrimp_type->id;
                    $productionWork->p_shrimp_size_id = $shrimp_size->id;
                    $productionWork->save();
                }

                /*Production Work Performance*/
                $productionWorkPerformance = new ProductionWorkPerformance();
                $productionWorkPerformance->p_work_id = $productionWork->id;
                $productionWorkPerformance->em_id = $random_employees->random()->em_id;
                $productionWorkPerformance->weight = random_int(1, 4);
                $productionWorkPerformance->created_by_user_id = 1;
                $productionWorkPerformance->updated_by_user_id = 1;
                $productionWorkPerformance->save();
            });
        }
        return redirect()->route('random.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
