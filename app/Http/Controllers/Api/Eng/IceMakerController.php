<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\IceMaker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IceMakerController extends Controller
{
    //Get Record
    public function getRecordByDate($date)
    {
        $freezer1_m_12_used = 0;
        $freezer2_m_2_used = 0;
        $freezer3_m_14_used = 0;
        $dateInput = Carbon::createFromFormat('Y-m-d', $date);
        Carbon::setTestNow($dateInput);
        $yesterday = Carbon::yesterday()->toDateString();
        $last_yesterday_used = IceMaker::whereDate('date', $yesterday)
            ->get()->sortBy('time_record', SORT_NATURAL)->values()->last();
        $records = IceMaker::whereDate('date', $date)
            ->get()->sortBy('time_record', SORT_NATURAL)->values();
        $i = 0;
        foreach ($records as $record) {
            if ($i == 0) {
                $record->freezer1_used = CalculateController::getUsed($record->freezer1_m_12, ($last_yesterday_used) ? $last_yesterday_used->freezer1_m_12 : null);
                $record->freezer2_used = CalculateController::getUsed($record->freezer2_m_2, ($last_yesterday_used) ? $last_yesterday_used->freezer2_m_2 : null);
                $record->freezer3_used = CalculateController::getUsed($record->freezer3_m_14, ($last_yesterday_used) ? $last_yesterday_used->freezer3_m_14 : null);
            } else {
                $record->freezer1_used = CalculateController::getUsed($record->freezer1_m_12, $records[$i - 1]->freezer1_m_12);
                $record->freezer2_used = CalculateController::getUsed($record->freezer2_m_2, $records[$i - 1]->freezer2_m_2);
                $record->freezer3_used = CalculateController::getUsed($record->freezer3_m_14, $records[$i - 1]->freezer3_m_14);
            }
            $i++;
        }
        if ($records->count() > 0 && $last_yesterday_used != null) {
            $last_record = $records->last();
            $freezer1_m_12_used = CalculateController::getDailyUsed($last_record->freezer1_m_12, $last_yesterday_used->freezer1_m_12);
            $freezer2_m_2_used = CalculateController::getDailyUsed($last_record->freezer2_m_2, $last_yesterday_used->freezer2_m_2);
            $freezer3_m_14_used = CalculateController::getDailyUsed($last_record->freezer3_m_14, $last_yesterday_used->freezer3_m_14);
        }
        $results = collect([
            'data' => $records,
            'freezer1_m_12_used' => $freezer1_m_12_used,
            'freezer2_m_2_used' => $freezer2_m_2_used,
            'freezer3_m_14_used' => $freezer3_m_14_used
        ]);
        return response()->json($results);
    }

    //Add Record
    public function addRecord(Request $request)
    {
        $result = IceMaker::create([
            'date' => $request->input('date'),
            'time_record' => $request->input('time_record'),
            'real_time_record' => $request->input('real_time_record'),
            'freezer1_m_12' => $request->input('freezer1_m_12'),
            'freezer2_m_2' => $request->input('freezer2_m_2'),
            'recei_no1' => $request->input('recei_no1'),
            'freezer3_m_14' => $request->input('freezer3_m_14')
        ]);
        return response()->json($result);
    }

    //Update Record
    public function updateRecord(Request $request)
    {
        $result = IceMaker::where('id', $request->input('id'))
            ->update([
                'date' => $request->input('date'),
                'time_record' => $request->input('time_record'),
                'real_time_record' => $request->input('real_time_record'),
                'freezer1_m_12' => $request->input('freezer1_m_12'),
                'freezer2_m_2' => $request->input('freezer2_m_2'),
                'recei_no1' => $request->input('recei_no1'),
                'freezer3_m_14' => $request->input('freezer3_m_14')
            ]);
        return response()->json($result);
    }

    //Delete Record
    public function deleteRecord($id)
    {
        $result = IceMaker::where('id', $id)->delete();
        return response()->json($result);
    }

    //Get Monthly Results
    public static function getMonthlyResult($year, $month)
    {
        $freezer1_m12_used = 0;
        $freezer2_m2_used = 0;
        $freezer3_m14_used = 0;

        //Last Month
        $dateInput = Carbon::createFromFormat('Y-m-d', $year . '-' . $month . '-1');
        Carbon::setTestNow($dateInput);
        $last_month = Carbon::yesterday()->month;
        $last_year = Carbon::yesterday()->year;
        $last_month_records = IceMaker::whereYear('date', $last_year)
            ->whereMonth('date', $last_month)
            ->get()->sortBy('date', SORT_NATURAL)->values()->groupBy('date');
        //End Last Month
        //Get Current Month
        $records = IceMaker::whereYear('date', $year)
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
                        $freezer1_m12_used = CalculateController::getDailyUsed($result['last_record']['freezer1_m_12'], $last_month_records['freezer1_m_12']);
                        $freezer2_m2_used = CalculateController::getDailyUsed($result['last_record']['freezer2_m_2'], $last_month_records['freezer2_m_2']);
                        $freezer3_m14_used = CalculateController::getDailyUsed($result['last_record']['freezer3_m_14'], $last_month_records['freezer3_m_14']);
                    }
                } else {
                    $freezer1_m12_used = CalculateController::getDailyUsed($results[$i]['last_record']['freezer1_m_12'], $results[$i - 1]['last_record']['freezer1_m_12']);
                    $freezer2_m2_used = CalculateController::getDailyUsed($results[$i]['last_record']['freezer2_m_2'], $results[$i - 1]['last_record']['freezer2_m_2']);
                    $freezer3_m14_used = CalculateController::getDailyUsed($results[$i]['last_record']['freezer3_m_14'], $results[$i - 1]['last_record']['freezer3_m_14']);
                }
                $result->put('used',[
                   'freezer1_m12_used'=>$freezer1_m12_used,
                    'freezer2_m2_used'=>$freezer2_m2_used,
                    'freezer3_m14_used'=>$freezer3_m14_used
                ]);
                $i++;
            }
        }
        $results=collect([
           'data'=>$results,
            'init'=>[
                'freezer1_m12_used'=>0,
                'freezer2_m2_used'=>0,
                'freezer3_m14_used'=>0
            ]
        ]);
        return $results;
    }

}
