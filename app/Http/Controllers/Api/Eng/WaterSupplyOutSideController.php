<?php

namespace App\Http\Controllers\Api\Eng;

use App\Models\Eng\WaterSupplyOutSide;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WaterSupplyOutSideController extends Controller
{
    //Get Supply By Date
    public function getSupplyByDate($date){
        $supplies=WaterSupplyOutSide::whereDate('date',$date)
            ->orderBy('time_record','asc')
            ->get();
        return response()->json($supplies);
    }
    //Get Supply By Month
    public function getSupplyByMonth(Request $request){
        $supplies=WaterSupplyOutSide::whereYear('date',$request->input('year'))
            ->whereMonth('date',$request->input('month'))
            ->get();
        return response()->json($supplies);
    }
    //Add Supply
    public function addSupply(Request $request){
        $result= WaterSupplyOutSide::create( [
            'date'=>$request->input('date'),
            'time_record'=>$request->input('time_record'),
            'real_time_record' =>$request->input('real_time_record'),
            'm_pwa'=>$request->input('m_pwa'),
            'm_15'=>$request->input('m_15'),
            'm_17'=>$request->input('m_17'),
            'm_18'=>$request->input('m_18'),
            'm_19'=>$request->input('m_19'),
            'm_20'=>$request->input('m_20'),
            'm_21'=>$request->input('m_21')
        ]);
        return response()->json($result);
    }

    //Update Supply
    public function updateSupply(Request $request){
        $result= WaterSupplyOutSide::where('id',$request->input('id'))
            ->update( [
                'date'=>$request->input('date'),
                'time_record'=>$request->input('time_record'),
                'real_time_record' =>$request->input('real_time_record'),
                'm_pwa'=>$request->input('m_pwa'),
                'm_15'=>$request->input('m_15'),
                'm_17'=>$request->input('m_17'),
                'm_18'=>$request->input('m_18'),
                'm_19'=>$request->input('m_19'),
                'm_20'=>$request->input('m_20'),
                'm_21'=>$request->input('m_21')
        ]);
        return response()->json($result);
    }
    //Delete Supply
    public function deleteSupply($id){
        $result=WaterSupplyOutSide::where('id',$id)->delete();
        return response()->json($result);
    }

}
