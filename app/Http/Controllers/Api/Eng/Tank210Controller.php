<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\Tank210;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Tank210Controller extends Controller
{
    //Get Record
    public function getRecordByDate($date)
    {
        $mm3_used=0;
        //Date
        $dateInput = Carbon::createFromFormat('Y-m-d', $date);
//        Carbon::setTestNow($dateInput);
//        $yesterday = Carbon::yesterday()->toDateString();
        $yesterday=$dateInput->subDay(1)->toDateString();
        $last_yesterday_meter = Tank210::whereDate('date', $yesterday)
            ->get()
            ->sortBy('time_record', SORT_NATURAL)->values()->last();
        if ($last_yesterday_meter != null) {
            $last_yesterday_meter->zero_time_record = '0:00';
        }
        $records = Tank210::whereDate('date', $date)
            ->get()->sortBy('time_record', SORT_NATURAL)->values();
        if($records->count()>0 && $last_yesterday_meter!=null){
            $last_record=$records->last();
            $mm3_used=CalculateController::getDailyUsed($last_record->mm_3,$last_yesterday_meter->mm_3);
        }
        $results=collect([
           'data'=>$records,
            'mm3_used'=>$mm3_used,
            'last'=>$last_yesterday_meter,
            'yesterday'=>$yesterday,
            'yesterday_meter'=>$last_yesterday_meter,
            'date'=>$date
        ]);
        return response()->json($results);
    }

    //Add Record
    public function addRecord(Request $request)
    {
        $result = Tank210::create([
            'date' => $request->input('date'),
            'time_record' => $request->input('time_record'),
            'real_time_record' => $request->input('real_time_record'),
            'mm_3' => $request->input('mm_3'),
            'level' => $request->input('level')
        ]);
        return response()->json($result);
    }

    //Update Record
    public function updateRecord(Request $request)
    {
        $result = Tank210::where('id', $request->input('id'))
            ->update([
                'date' => $request->input('date'),
                'time_record' => $request->input('time_record'),
                'real_time_record' => $request->input('real_time_record'),
                'mm_3' => $request->input('mm_3'),
                'level' => $request->input('level')
            ]);
        return response()->json($result);
    }

    //Delete Record
    public function deleteRecord($id)
    {
        $result = Tank210::where('id', $id)->delete();
        return response()->json($result);
    }

    //Get Monthly Results
    public static function getMonthlyResult($year, $month)
    {
        $mm3_used = 0;
        //Last Month
        $dateInput = Carbon::createFromFormat('Y-m-d', $year . '-' . $month . '-1');
//        Carbon::setTestNow($dateInput);
//        $last_month = Carbon::yesterday()->month;
//        $last_year = Carbon::yesterday()->year;
        $last_month=$dateInput->subDay(1)->month;
        $last_year=$dateInput->subDay(1)->year;
        $last_month_records = Tank210::whereYear('date', $last_year)
            ->whereMonth('date', $last_month)
            ->get()->sortBy('date', SORT_NATURAL)->values()->groupBy('date');
        //End Last Month
        //Get Current Month
        $records = Tank210::whereYear('date', $year)
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
                        $mm3_used = CalculateController::getDailyUsed($result['last_record']['mm_3'], $last_month_records['mm_3']);
                    }
                } else {
                    $mm3_used = CalculateController::getDailyUsed($results[$i]['last_record']['mm_3'], $results[$i - 1]['last_record']['mm_3']);

                }

                $result->put('used',['mm3_used'=> $mm3_used]);
                $i++;
            }
        }
        $results=collect([
           'data'=>$results,
            'init'=>[
             'mm3_used'=>0
            ]
        ]);
        return $results;
    }

}
