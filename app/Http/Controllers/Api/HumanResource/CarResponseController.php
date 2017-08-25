<?php

namespace App\Http\Controllers\Api\HumanResource;

use App\Models\HumanResource\CarRequest;
use App\Models\HumanResource\CarRequestStatus;
use App\Models\HumanResource\CarResponse;
use App\Models\HumanResource\CarType;
use App\Models\HumanResource\CarUsage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class CarResponseController extends Controller
{
    /*Approve Request*/
    public function approveRequest(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            CarResponse::whereIn('id', $request->input('response_ids'))
                ->update([
                    'status_id' => 3,
                    'car_access_status_id' => 1,
                    'approved_by_user_id' => $request->input('user_id')
                ]);
            CarRequest::whereIn('id', $request->input('request_ids'))->update(['status_id' => 3]);
        });
        return response()->json($result);
    }

    /*Assign Car*/
    public function assignCar(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $carRequestList = [];
            $quantity = 0;
            $carResponse = CarResponse::create([
                'date' => $request->input('date'),
                'time' => $request->input('time'),
                'status_id' => 2,//step 2
                'car_id' => $request->input('car_id'),
                'driver_id' => $request->input('driver_id'),
                'destination' => $request->input('destination'),
                'details' => $request->input('details'),
                'assigned_by_user_id' => $request->input('user_id')
            ]);
            foreach ($request->input('car_request_ids') as $requestID) {
                $carRequestList[] = [
                    'car_request_id' => $requestID
                ];
            }
            $carResponse->carRequest()->attach($carRequestList);
            //Update Request Status
            $carRequest = CarRequest::whereIn('id', $request->input('car_request_ids'))->update(
                ['status_id' => 2]//Step 2
            );
            // Decrease Quantity
            $carType = CarType::where('id', $request->input('car_type_id'))->first();
            $carType->quantity = $carType->quantity - 1;
            $carType->save();
        });
        return response()->json($result);
    }

    /*Cancel Approved Response*/
    public function cancelApprovedResponse($response_id)
    {
        $result = DB::transaction(function () use ($response_id) {
            $carResponse = CarResponse::where('id', $response_id)->first();
            $carResponse->status_id = 2;
            $carResponse->save();
            $carResponse->carRequest()->update(
                ['status_id' => '2']
            );
            CarUsage::where('response_id',$response_id)->delete();
        });
        return response()->json($result);
    }

    /*Delete Assign CAr*/
    public function deleteAssignedRequest($response_id)
    {
        $result = DB::transaction(function () use ($response_id) {
            $carResponse = CarResponse::where('id', $response_id)->first();
            $carResponse->carRequest()->update(
                ['status_id' => '1']
            );
            $carResponse->carRequest()->detach();
            $carResponse->delete();
        });
        return response()->json($result);
    }

    /*Delete Response*/
    public function deleteResponse(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            DB::table('car_response_request')->whereIn('id', $request->input('response_id'))->delete();
            CarResponse::whereIn('id', $request->input('response_id'))->delete();
        });
        return response()->json($result);
    }

    /*Delete Request*/
    public function deleteResponseRequest(Request $request)
    {
        $carRequestIDList = [];
        foreach ($request->input('car_request_ids') as $carRequestID) {
            $carRequestIDList[] = [
                'car_request_id' => $carRequestID
            ];
        }
        $carResponse = CarResponse::with('carRequest')->where('id', $request->input('response_id'))->first();
        $carResponse->carRequest()->detach($carRequestIDList);
        return response()->json($carResponse);
    }

    /*Get Car Request*/
    public function getCarRequest($status)
    {
        $carRequest = null;
        if ($status == 'all') {
            $carRequest = CarRequest::with('carType', 'status', 'division', 'employee', 'rank', 'passenger.employee')->orderBy('start_date', 'desc')->paginate(100);
        } else {
            $carRequest = CarRequest::with('carType', 'status', 'division', 'employee', 'rank', 'passenger.employee')->where('status_id', $status)->orderBy('start_date', 'desc')->paginate(100);
        }
        return response()->json($carRequest);
    }

    /*Get Response*/
    public function getCarResponse($status_id)
    {
        $carResponse = CarResponse::with('car', 'driver', 'status', 'carRequest.employee', 'carRequest.division', 'assigner', 'approver')
            ->where('status_id', $status_id)
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->paginate(100);
        return response()->json($carResponse);
    }

    /*Get Car Response By User*/
    public function getCarResponseByUser(Request $request)
    {
        $carResponse = null;
        if ($request->input('status_id') == 2) {
            $carResponse = CarResponse::with('car', 'driver', 'status', 'carRequest.employee', 'carRequest.division', 'assigner', 'approver')
                ->where('assigned_by_user_id', $request->input('user_id'))
                ->where('status_id', $request->input('status_id'))
                ->orderBy('date', 'desc')
                ->orderBy('time', 'desc')
                ->paginate(100);
        } else if ($request->input('status_id') == 3) {
            $carResponse = CarResponse::with('car', 'driver', 'status', 'carRequest.employee', 'carRequest.division', 'assigner', 'approver')
                ->where('approved_by_user_id', $request->input('user_id'))
                ->where('status_id', $request->input('status_id'))
                ->orderBy('date', 'desc')
                ->orderBy('time', 'desc')
                ->paginate(100);
        }

        return response()->json($carResponse);
    }

    /*Get Car Request Status*/
    public function getCarRequestStatus()
    {
        $statuses = CarRequestStatus::all();
        return response()->json($statuses);
    }

    /*Search By Date*/
    public function searchByDate(Request $request)
    {
        $carRequest = null;
        if ($request->input('status') == 1) {
            $carRequest = CarRequest::with('division', 'employee', 'status', 'carType')
                ->where('start_date', $request->input('start_date'))
                ->where('status_id', $request->input('status'))
                ->orderBy('updated_at', 'desc')
                ->paginate();
        } else {
            $carRequest = CarResponse::with('car', 'driver', 'status', 'carRequest.employee', 'carRequest.division', 'assigner', 'approver')
                ->where('date', $request->input('start_date'))
                ->where('status_id', $request->input('status'))
                ->orderBy('date', 'desc')
                ->orderBy('time', 'desc')
                ->paginate();
        }
        return response()->json($carRequest);
    }

    /*Update Response*/
    public function updateResponse(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            CarResponse::where('id', $request->input('response_id'))
                ->update([
                    'date' => $request->input('date'),
                    'time' => $request->input('time'),
                    'car_id' => $request->input('car_id'),
                    'driver_id' => $request->input('driver_id'),
                    'destination' => $request->input('destination'),
                    'details' => $request->input('details')
                ]);
        });
        return response()->json($result);
    }

}
