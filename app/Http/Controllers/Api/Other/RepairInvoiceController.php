<?php

namespace App\Http\Controllers\Api\Other;

use App\Models\RepairInvoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use DB;
use File;

class RepairInvoiceController extends Controller
{
    private $Waiting=1;
    private $Approved=2;
    private $Reject=3;
    //Get Record By Date
    public function getRecordByDate($date){
        $records=RepairInvoice::with('sender','approver','status','division')->whereDate('date',$date)
            ->orderBy('date','asc')->get();
        return response()->json($records);
    }
    //Get Response By Date
    public function getResponseByDate(Request $request){
        $records=RepairInvoice::with('sender','approver','status','division')->whereDate('date',$request->input('date'))
            ->where('status_id',$request->input('status_id'))
            ->orderBy('date','asc')
            ->get();
        return response()->json($records);
    }
    //Add Request
    public function addRequest(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $strRand = str_random(4);
            $invoice = RepairInvoice::create([
                'date' => $request->input('date'),
                'time' => $request->input('time'),
                'repair_receiver_id'=>$request->input('repair_receiver_id'),
                'status_id' => $this->Waiting,
                'division_id' => $request->input('division_id'),
                'sender_id' => $request->input('sender_id'),
                'item' => $request->input('item'),
                'item_details' => $request->input('item_details'),
            ]);
            if ($request->input('image')) {
                $path = 'images/repair_invoice/' . $request->input('date') . '_' . $strRand . $request->input('item') . '.jpg';
                $newImage = Image::make($request->input('image'))->save($path);
                if ($newImage) {
                    $invoice->image = $path;
                    $invoice->save();
                }
            }
        });
        return response()->json($result);
    }

    //Edit Request
    public function editRequest(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $strRand = str_random(4);
            $invoice = RepairInvoice::where('id', $request->input('id'))
                ->first();
            $invoice->update([
                    'date' => $request->input('date'),
                    'time' => $request->input('time'),
                    'repair_receiver_id'=>$request->input('repair_receiver_id'),
                    'item' => $request->input('item'),
                    'item_details' => $request->input('item_details'),
                ]);
            if ($request->input('image')) {
                $path = 'images/repair_invoice/' . $request->input('date') . '_' . $strRand . $request->input('item') . '.jpg';
                $newImage = Image::make($request->input('image'))->save($path);
                if ($newImage) {
                    $invoice->image = $path;
                    $invoice->save();
                }
            }
        });
        return response()->json($result);
    }

    //Delete Request
    public function deleteRequest($id){
        $result=RepairInvoice::where('id',$id)->delete();
        return response()->json($result);
    }
    //Approve
    public function approveRequest(Request $request)
    {
        $invoice = RepairInvoice::where('id', $request->input('id'))
            ->update([
                'status_id' => $this->Approved,
                'approver_id' => $request->input('user_id'),
            ]);

        return response()->json($invoice);
    }

    //Cancel Approved
    public function cancelApproved($id)
    {
        $invoice = RepairInvoice::where('id', $id)
            ->update([
                'status_id' => $this->Waiting,
                'approver_id'=>null
            ]);
        return response()->json($invoice);
    }

    //Reject Request
    public function rejectRequest($id)
    {
        $invoice = RepairInvoice::where('id', $id)
            ->update([
                'status_id' => $this->Reject//reject Status
            ]);
        return response()->json($invoice);
    }

    //Delete Photo
    public function deletePhoto($id){
        $result=DB::transaction(function() use($id){
            $invoice=RepairInvoice::where('id',$id)->first();
            $delete_result=File::delete($invoice->image);
            if($delete_result){
                $invoice->image=null;
                $invoice->save();
            }
        });
        return response()->json($result);
    }
}
