<?php

namespace App\Http\Controllers\Api\Other;

use App\Models\RepairInvoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use DB;

class RepairInvoiceController extends Controller
{
    private $Waiting=1;
    private $Approved=2;
    private $Reject=3;
    //Get Record By Date
    public function getRecordByDate($date){
        $records=RepairInvoice::with('status')->whereDate('date',$date)
            ->orderBy('date','asc')->get();
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
                'status_id' => $this->Waiting,
                'division_id' => $request->input('division_id'),
                'sender_id' => $request->input('sender_id'),
                'item' => $request->input('item'),
                'item_details' => $request->input('item_details'),
            ]);
            if ($request->input('image')) {
                $path = 'images/repair_invoice/' . $request->input('date') . '-' . $strRand . $request->input('item') . '.jpg';
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
                ->update([
                    'date' => $request->input('date'),
                    'time' => $request->input('time'),
                    'division_id' => $request->input('division_id'),
                    'item' => $request->input('item'),
                    'item_details' => $request->input('item_details'),
                ]);
            if ($request->input('image')) {
                $path = 'images/repair_invoice/' . $request->input('date') . '-' . $strRand . $request->input('item') . '.jpg';
                $newImage = Image::make($request->input('image'))->save($path);
                if ($newImage) {
                    $invoice->image = $path;
                    $invoice->save();
                }
            }
        });
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
                'status_id' => $this->Waiting
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
    }
}
