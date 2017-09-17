<?php

namespace App\Http\Controllers\Api\Eng\Result;

use App\Models\Eng\ColdStorageTemp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ColdStorageResultController extends Controller
{
    //Get Record
    public function getResultByDate($date)
    {
        $cs1_max = 0;
        $cs1_min = 0;
        $cs2_max = 0;
        $cs2_min = 0;
        $records = ColdStorageTemp::whereDate('date', $date)
            ->get()->sortBy('time_record', SORT_NATURAL)->values();
        if ($records->count() > 0) {
            $cs1_max = $records->where('cs1_rm', $records->max('cs1_rm'))->first();
            $cs1_min = $records->where('cs1_rm', $records->min('cs1_rm'))->first();
            $cs2_max = $records->where('cs2_rm', $records->max('cs2_rm'))->first();
            $cs2_min = $records->where('cs2_rm', $records->min('cs2_rm'))->first();
        }
        $results = collect([
            'data' => $records,
            'cs1_max' => $cs1_max,
            'cs1_min' => $cs1_min,
            'cs2_max' => $cs2_max,
            'cs2_min' => $cs2_min,
            'date'=>$date
        ]);
        return response()->json($results);
    }
}
