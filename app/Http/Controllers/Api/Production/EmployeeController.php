<?php

namespace App\Http\Controllers\Api\Production;

use App\Models\Employee;
use App\Models\Production\ProductionEmployee;
use App\Models\Production\ProductionEmployeeGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class EmployeeController extends Controller
{
    /*Get Groups*/
    public function getGroups(){
        $groups=ProductionEmployeeGroup::all();
        return response()->json($groups);
    }
    /*Get Group Member*/
    public function getGroupMembers($name){
        $employees=ProductionEmployee::with('employee')
            ->where('group',$name)->orderBy('em_id')->get();
        return response()->json($employees);
    }

    /*Get Non Group Employee*/
    public function getNonGroupEmployee($division_id){
        $productionEmployee=ProductionEmployee::pluck('em_id')->all();
        $nonGroupEmployee=Employee::where('division_id',$division_id)
            ->whereNotIn('em_id',$productionEmployee)->orderBy('em_id')->paginate(40);
        return response()->json($nonGroupEmployee);
    }
    /*Get All Division Employee*/
    public function getAllDivisionEmployee($division_id){
        $productionEmployee=Employee::with('productionEmployee')
            ->where('division_id',$division_id)
            ->orderBy('em_id')->paginate(40);
        return response()->json($productionEmployee);
    }
    /*Add New Group Member*/
    public function addGroupMember(Request $request){
        $productionEmployee=ProductionEmployee::pluck('em_id')->all();
        $nonGroupMembers=array_diff($request->input('employees'),$productionEmployee);
        $newMembers=[];
        foreach ($nonGroupMembers as $nonGroupMember){
            $newMembers[]=[
                'em_id'=>$nonGroupMember,
                'group'=>$request->input('group')
            ];
        }
        $addNewMember=new ProductionEmployee();
        $addNewMember->insert($newMembers);
        return response($addNewMember);
    }
    /*Change Group Member*/
    public function changeGroupMember(Request $request){
        $productionEmployees=ProductionEmployee::whereIn('em_id',$request->input('employees'))
            ->update(['group'=>$request->input('group')]);
        return response($productionEmployees);
    }
    /*Delete Group Member*/
    public function deleteGroupMember(Request $request){
        $productionEmployees=ProductionEmployee::whereIn('em_id',$request->input('employees'))
            ->delete();
        return response($productionEmployees);
    }

}
