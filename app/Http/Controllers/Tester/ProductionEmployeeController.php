<?php

namespace App\Http\Controllers\Tester;

use App\Models\Employee;
use App\Models\Production\ProductionEmployee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductionEmployeeController extends Controller
{
    public function getNonGroupEmployee(){
        $productionEmployee=ProductionEmployee::pluck('em_id')->all();
        $nonGroupEmployee=Employee::where('division_id',9)
            ->whereNotIn('em_id',$productionEmployee)->orderBy('em_id')->paginate(20);
        dd($nonGroupEmployee);
//        return response()->json($nonGroupEmployee);
    }
    /*Add Production Employee with firstOrCreate*/
    public function addWithFirstOrCreate(){
        $productionEmployee=ProductionEmployee::pluck('em_id')->all();
        $emIDList=['7008','7010','7024'];
//        $productionEmployee=array_get($productionEmployee);
        $emIDList=array_diff($emIDList,$productionEmployee);
//        dd($emIDList);
        $groupID=1;
        $newMembers=[];
        foreach ($emIDList as $emID) {
            $newMembers[] = [
                'em_id' => $emID,
                'group_id' => $groupID
            ];
        }
//        $newMembers=[
//            'em_id'=>7008,
//            'group_id'=>'1'
//        ];
        $addNewMember=new ProductionEmployee();
//        $addNewMember->firstOrCreate($newMembers);
        $addNewMember->insert($newMembers);
        dd($addNewMember);
//        dd($newMembers);
    }
    /*Update Production Employee*/
    public function changeGroupMember(){
        $emID=['7039','7008','7035','7010'];
        $productionEmployee=ProductionEmployee::whereIn('em_id',$emID)
            ->get();
        dd($productionEmployee);
    }
}
