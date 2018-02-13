<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\Boiler;
use App\Models\Eng\BoilerDetails;
use App\Models\Eng\BoilerTime;
use Carbon\Carbon;
//use function foo\func;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Collection;

class BoilerController extends Controller
{
    //Get Record
    public function getRecordByDate($date)
    {
        $global_details_used = collect([]);
        $boiler_1 = collect([]);
        $boiler_1_list = collect([]);
        $boiler_2 = collect([]);
        $boiler_2_list = collect([]);
        $timeRecords = collect([]);
        $water_meter_used_time=null;
        $water_meter_used=null;
        $oil_meter_used=null;
        $water_oil_used_time=0;

        $records = Boiler::with('timeRecords.allDetails')->whereDate('date', $date)
            ->first();
        if ($records) {

            $timeRecords = $records->timeRecords;
            foreach ($timeRecords as $timeRecord) {
                $timeRecord->boiler_1 = $timeRecord->allDetails->where('boiler_number', 1)->first();
                $timeRecord->boiler_2 = $timeRecord->allDetails->where('boiler_number', 2)->first();
            }
            $timeRecords = $timeRecords->sortBy('time_record', SORT_NATURAL)->values();
            $boiler_1 = $timeRecords->filter(function ($item) {
                return $item->boiler_1;
            })->values();
            $boiler_2 = $timeRecords->filter(function ($item) {
                return $item->boiler_2;
            })->values();
            foreach ($boiler_1 as $item) {
                $temp = [
                    'eng_boiler_id' => $item->eng_boiler_id,
                    'ot_level' => $item->ot_level,
                    'time_record' => $item->time_record,
                    'details' => $item->boiler_1
                ];
                $boiler_1_list->push($temp);
            }
            foreach ($boiler_2 as $item) {
                $temp = [
                    'eng_boiler_id' => $item->eng_boiler_id,
                    'ot_level' => $item->ot_level,
                    'time_record' => $item->time_record,
                    'details' => $item->boiler_2
                ];
                $boiler_2_list->push($temp);
            }

            //Calculate Water Oil Used
            if ($records->water_oil_start_time && $records->water_oil_end_time) {
                $water_meter_start_time = Carbon::createFromFormat('H:i:s', $records->water_oil_start_time);
                $water_meter_end_time = Carbon::createFromFormat('H:i:s', $records->water_oil_end_time);
                $water_oil_used_time=$water_meter_end_time->diffInMinutes($water_meter_start_time)/60;

                $water_meter_used = $records->water_meter_end - $records->water_meter_start;
                $oil_meter_used = $records->oil_meter_end - $records->oil_meter_start;
            }
            $fw_boiler_1_used=null;
            $fw_boiler_2_used=null;
            if($boiler_1_list&&$boiler_1_list->count()>1){
                $fw_boiler_1_used=$boiler_1_list->last()['details']['fw_meter']-$boiler_1_list->first()['details']['fw_meter'];
            }
            if($boiler_2_list&&$boiler_2_list->count()>1){
                $fw_boiler_2_used=$boiler_2_list->last()['details']['fw_meter'] - $boiler_2_list->first()['details']['fw_meter'];
            }
//            $boiler_1_list->fist()
            $global_details_used->put('fw_boiler_1_used',$fw_boiler_1_used);
            $global_details_used->put('fw_boiler_2_used',$fw_boiler_2_used);
            $global_details_used->put('water_meter_used', $water_meter_used);
            $global_details_used->put('oil_meter_used', $oil_meter_used);
            $global_details_used->put('water_oil_used_time', $water_oil_used_time);
        }
        $results = collect([
            'global_details' => $records,
            'data' => $timeRecords,
            'date' => $date,
            'boiler_1' => $boiler_1_list,
            'boiler_1_first'=>$boiler_1_list->first(),
            'boiler_1_last'=>$boiler_1_list->last(),
            'boiler_2' => $boiler_2_list,
            'global_details_used' => $global_details_used
        ]);
        return response()->json($results);
    }

    //Add Record
    public function addRecord(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $boiler = Boiler::firstOrCreate([
                'date' => $request->input('date')
            ]);
            $boilerTimeRecord = BoilerTime::updateOrcreate([
                'eng_boiler_id' => $boiler->id,
                'time_record' => $request->input('time_record')],
                [
                    'ot_level' => $request->input('ot_level')
                ]);
            $boilerDetails = BoilerDetails::create([
                'eng_boiler_time_id' => $boilerTimeRecord->id,
                'boiler_number' => $request->input('boiler_number'),
                'sp_digital' => $request->input('sp_digital'),
                'sp_header' => $request->input('sp_header'),
                'oph_burner' => $request->input('oph_burner'),
                'fw_meter' => $request->input('fw_meter'),
                'fw_tank_l' => $request->input('fw_tank_l'),
                'cl_tank_1' => $request->input('cl_tank_1'),
                'cl_tank_2' => $request->input('cl_tank_2'),
                'st_digital' => $request->input('st_digital'),
                'remarks' => $request->input('remarks')
            ]);
        });
        return response()->json($result);
    }

    //Add Global
    public function addGlobalDetails(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $newRecord = Boiler::updateOrCreate(
                ['date' => $request->input('date')],
                ['water_oil_start_time' => $request->input('water_oil_start_time'),
                    'water_oil_end_time' => $request->input('water_oil_end_time'),
                    'water_meter_start' => $request->input('water_meter_start'),
                    'water_meter_end' => $request->input('water_meter_end'),
                    'oil_meter_start' => $request->input('oil_meter_start'),
                    'oil_meter_end' => $request->input('oil_meter_end'),
                    'blow_down_number' => $request->input('blow_down_number'),
                    'blow_down_sec' => $request->input('blow_down_sec'),
                    'blow_down_1_time' => $request->input('blow_down_1_time'),
                    'blow_down_2_time' => $request->input('blow_down_2_time'),
                    'safety_vale_time' => $request->input('safety_vale_time')]
            );
        });
        return response()->json($result);
    }

    //Update Record
    public function updateRecord(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $boiler = Boiler::firstOrCreate([
                'date' => $request->input('date')
            ]);
            $boilerTimeRecord = BoilerTime::updateOrcreate([
                'eng_boiler_id' => $boiler->id,
                'time_record' => $request->input('time_record')],
                [
                    'ot_level' => $request->input('ot_level')
                ]);
            $boilerDetails = BoilerDetails::where('id', $request->input('id'))
                ->update([
                    'eng_boiler_time_id' => $boilerTimeRecord->id,
                    'sp_digital' => $request->input('sp_digital'),
                    'sp_header' => $request->input('sp_header'),
                    'oph_burner' => $request->input('oph_burner'),
                    'fw_meter' => $request->input('fw_meter'),
                    'fw_tank_l' => $request->input('fw_tank_l'),
                    'cl_tank_1' => $request->input('cl_tank_1'),
                    'cl_tank_2' => $request->input('cl_tank_2'),
                    'st_digital' => $request->input('st_digital'),
                    'remarks' => $request->input('remarks')
                ]);
        });
        return response()->json($result);
    }

    //Delete Record
    public function deleteRecord($id)
    {
//        $result = BoilerDetails::where('id', $id)->delete();
        $result = DB::transaction(function () use ($id) {
            $record = BoilerDetails::with('timeRecord.allDetails')
                ->where('id', $id)
                ->first();
            if ($record->timeRecord->allDetails->count() < 2) {// has only one boiler maybe 1 or 2 then delete this time_record too
                BoilerTime::destroy($record->timeRecord->id);
                BoilerDetails::destroy($id);
            } else { // delete only this details
                BoilerDetails::destroy($id);
            }
        });

        return response()->json($result);
    }

    //Get Monthly Result
    public static function getMonthlyResult($year, $month)
    {
        $boiler1_meter_used = 0;
        $boiler2_meter_used = 0;
        //Last Month
        $dateInput = Carbon::createFromFormat('Y-m-d', $year . '-' . $month . '-1');
//        Carbon::setTestNow($dateInput);
//        $last_month = Carbon::yesterday()->month;
//        $last_year = Carbon::yesterday()->year;
        $last_month = $dateInput->subDay(1)->month;
        $last_year = $dateInput->subDay(1)->year;
        $last_month_records = Boiler::whereYear('date', $last_year)
            ->whereMonth('date', $last_month)
            ->get()->sortBy('date', SORT_NATURAL)->values()->groupBy('date');
        //End Last Month
        //Get Current Month
        $records = Boiler::whereYear('date', $year)
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
                        $boiler1_meter_used = CalculateController::getDailyUsed($result['last_record']['boiler1_meter'], $last_month_records['boiler1_meter']);
                        $boiler2_meter_used = CalculateController::getDailyUsed($result['last_record']['boiler2_meter'], $last_month_records['boiler2_meter']);
                    }
                } else {
                    $boiler1_meter_used = CalculateController::getDailyUsed($results[$i]['last_record']['boiler1_meter'], $results[$i - 1]['last_record']['boiler1_meter']);
                    $boiler2_meter_used = CalculateController::getDailyUsed($results[$i]['last_record']['boiler2_meter'], $results[$i - 1]['last_record']['boiler2_meter']);

                }
                $result->put('used', [
                    'boiler1_meter_used' => $boiler1_meter_used,
                    'boiler2_meter_used' => $boiler2_meter_used
                ]);
                $i++;
            }
        }
        $results = collect([
            'data' => $results,
            'init' => [
                'boiler1_meter_used' => 0,
                'boiler2_meter_used' => 0
            ]
        ]);
        return $results;
    }


}
