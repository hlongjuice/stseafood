<?php

namespace App\Http\Controllers\Api\Production;

use App\Models\Employee;
use App\Models\Production\ProductionEmployee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class EmployeeController extends Controller
{
    /*Get Groups*/
    public function getGroups(){
        $groups=DB::table('production_employee')->select('group_id')
            ->groupBy('group_id')->orderBy('group_id','asc')->get();
        return response()->json($groups);
    }
    /*Get Group Member*/
    public function getGroupMembers($id){
        $employees=ProductionEmployee::with('employee')
            ->where('group_id',$id)->get();
        return response()->json($employees);
    }

}
