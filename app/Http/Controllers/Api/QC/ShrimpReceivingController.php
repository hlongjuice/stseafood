<?php

namespace App\Http\Controllers\Api\QC;

use App\Models\QC\QcShrimpReceiving;
use App\Models\QC\QcSupplierReceiving;
use App\Models\QC\QcWaterTempReceiving;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Exception;

class ShrimpReceivingController extends Controller
{
    public function addReceiving(Request $request)
    {
        $waterTempInput = [];
        foreach ($request->input('water_temp') as $tempInput) {
            $waterTempInput[] = [
                'value' => $tempInput['water_temp']
            ];
        }
        $result = DB::transaction(function () use ($request, $waterTempInput) {
            $supplierReceiving = QcSupplierReceiving::where('supplier_id', $request->input('supplier_id'))
                ->where('date', $request->input('date'))
                ->where('pond', $request->input('sp_pond'))
                ->where('code', $request->input('sp_code'))
                ->first();
            if ($supplierReceiving == null) {
                //Add to Supplier Receiving Table
                $supplierReceiving = QcSupplierReceiving::create([
                    'supplier_id' => $request->input('supplier_id'),
                    'pond' => $request->input('sp_pond'),
                    'code' => $request->input('sp_code'),
                    'date' => $request->input('date')
                ]);
            }

            $existRound = QcShrimpReceiving::where('qc_supplier_receiving_id', $supplierReceiving->id)
                ->where('round', $request->input('round'))->first();
            if ($existRound != null) {
                abort(500, 'ไม่สามารถบันทึกข้อมูลได้ มีการบันทึกเที่ยวที  ' . $request->input('round') . '  แล้ว');
            }
            //Add to Shrimp Receiving Table
            $shrimpReceiving = QcShrimpReceiving::create([
                'created_by_user_id' => $request->input('user_id'),
                'qc_supplier_receiving_id' => $supplierReceiving->id,
                'round' => $request->input('round'),
                'shrimp_size' => $request->input('shrimp_size'),
                'shrimp_big' => $request->input('shrimp_big'),
                'shrimp_small' => $request->input('shrimp_small'),
                'shrimp_uf' => $request->input('shrimp_uf'),
                'df_shrimp_dead' => $request->input('df_shrimp_dead'),
                'df_shrimp_soft_shell'=>$request->input('df_shrimp_soft_shell'),
                'df_shrimp_semi_soft' => $request->input('df_shrimp_semi_soft'),
                'df_shrimp_scar' => $request->input('df_shrimp_scar'),
                'df_shrimp_bk_line' => $request->input('df_shrimp_bk_line'),
                'df_shrimp_disabled' => $request->input('df_shrimp_disabled'),
//                'car_release' => $request->input('car_release'),
                'car_release_start'=>$request->input('car_release_start'),
                'car_release_end'=>$request->input('car_release_end'),
                'car_waiting_time' => $request->input('car_waiting_time'),
                'real_shrimp_dead' => $request->input('real_shrimp_dead'),
                'weight' => $request->input('weight')
            ]);
            //Add to Water Temp Receiving
            $shrimpReceiving->waterTemp()->createMany($waterTempInput);

        });
        return response()->json($result);
    }

    public function addExtraReceiving(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            QcSupplierReceiving::where('id', $request->input('receiving_id'))
                ->update([
                    'last_five_round_status' => $request->input('last_five_round_status'),
                    'real_shrimp_soft' => $request->input('real_shrimp_soft')
                ]);
        });
        return response()->json($result);
    }

    public function getReceiving(Request $request)
    {
        $receiving = QcSupplierReceiving::with(['shrimpReceiving' => function ($query) {
            $query->orderBy('round', 'asc');
        }, 'shrimpReceiving.waterTemp', 'supplier'])
            ->whereDate('date', $request->input('date'))
            ->orderBy('date', 'desc')
            ->get();

        return response()->json($receiving);
    }

    public function getShrimpReceivingByID(Request $request)
    {
        $receiving = QcSupplierReceiving::with('shrimpReceiving.waterTemp', 'supplier')
            ->where('id', $request->input('receiving_id'))
            ->whereDate('date', $request->input('date'))
            ->orderBy('date', 'desc')
            ->first();

        return response()->json($receiving);
    }

    //Update Shrimp Receiving
    public function updateShrimpReceiving(Request $request)
    {
        $waterTempInput = [];
        foreach ($request->input('water_temp') as $tempInput) {
            $waterTempInput[] = [
                'value' => $tempInput['water_temp']
            ];
        }
        $result = DB::transaction(function () use ($request, $waterTempInput) {
            //Add to Shrimp Receiving Table
            $shrimpReceiving = QcShrimpReceiving::where('id', $request->input('shrimp_receiving_id'))->first();
            if ($shrimpReceiving->round != $request->input('round')) {
//                abort(500,$request->input('qc_shrimp_receiving_id'));
              $existRound = QcShrimpReceiving::where('qc_supplier_receiving_id', $request->input('qc_supplier_receiving_id'))
                    ->where('round', $request->input('round'))->first();
                if ($existRound != null) {
                    abort(500, 'ไม่สามารถบันทึกข้อมูลได้ มีการบันทึกเที่ยวที  ' . $request->input('round') . '  แล้ว');
                }
            }
//            abort(500,$shrimpReceiving->round);
            $shrimpReceiving->update([
                'updated_by_user_id' => $request->input('user_id'),
                'round' => $request->input('round'),
                'shrimp_size' => $request->input('shrimp_size'),
                'shrimp_big' => $request->input('shrimp_big'),
                'shrimp_small' => $request->input('shrimp_small'),
                'shrimp_uf' => $request->input('shrimp_uf'),
                'df_shrimp_dead' => $request->input('df_shrimp_dead'),
                'df_shrimp_soft_shell'=>$request->input('df_shrimp_soft_shell'),
                'df_shrimp_semi_soft' => $request->input('df_shrimp_semi_soft'),
                'df_shrimp_scar' => $request->input('df_shrimp_scar'),
                'df_shrimp_bk_line' => $request->input('df_shrimp_bk_line'),
                'df_shrimp_disabled' => $request->input('df_shrimp_disabled'),
//                'car_release' => $request->input('car_release'),
                'car_release_start'=>$request->input('car_release_start'),
                'car_release_end'=>$request->input('car_release_end'),
                'car_waiting_time' => $request->input('car_waiting_time'),
                'real_shrimp_dead' => $request->input('real_shrimp_dead'),
                'weight' => $request->input('weight')
            ]);
            //Add to Water Temp Receiving
            $shrimpReceiving->waterTemp()->delete();
            $shrimpReceiving->waterTemp()->createMany($waterTempInput);

        });
        return response()->json($result);
    }

    //Update Supplier Receiving
    public function updateSupplierReceiving(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            return QcSupplierReceiving::where('id', $request->input('supplier_receiving_id'))
                ->update([
                    'date' => $request->input('date'),
                    'supplier_id' => $request->input('supplier_id'),
                    'pond' => $request->input('pond'),
                    'code' => $request->input('code')
                ]);
        });
        return response()->json($result);


    }

    //Delete Supplier Receiving
    public function deleteSupplierReceiving($id)
    {
        $shrimp_receiving_ids = [];
        $result = DB::transaction(function () use ($id, $shrimp_receiving_ids) {
            $shrimp_receiving = QcShrimpReceiving::where('qc_supplier_receiving_id', $id)->get();
            if ($shrimp_receiving != null) {
                $shrimp_receiving_ids = $shrimp_receiving->pluck('id');
                QcWaterTempReceiving::whereIn('qc_shrimp_receiving_id', $shrimp_receiving_ids)->delete();
                QcShrimpReceiving::destroy($shrimp_receiving_ids);
            }
            QcSupplierReceiving::where('id', $id)
                ->delete();
            return $shrimp_receiving_ids;
        });

        return response()->json($result);
//        $test=$result->shrimpReceiving;
    }

    public function deleteShrimpReceiving($id)
    {
        $result = DB::transaction(function () use ($id) {
            $shrimp_receiving = QcShrimpReceiving::where('id', $id)->first();
            if ($shrimp_receiving != null) {
                $shrimp_receiving->waterTemp()->delete();
                $shrimp_receiving->delete();
            }
        });
        return response()->json($result);
    }

    //Add Checker
    public function addChecker(Request $request){
        $result = DB::transaction(function () use ($request) {
            QcSupplierReceiving::where('id', $request->input('receiving_id'))
                ->update([
                    'recorder'=>$request->input('recorder'),
                    'checker'=>$request->input('checker'),
                    'approver'=>$request->input('approver'),
                    'report_number'=>$request->input('report_number')
                ]);
        });
        return response()->json($result);
    }

}
