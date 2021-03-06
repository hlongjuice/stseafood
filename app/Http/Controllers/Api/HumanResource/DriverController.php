<?php

namespace App\Http\Controllers\Api\HumanResource;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DriverController extends Controller
{
//    public $DEPARTMENT_ID=3;
    public function getAllDriver(){
        $department=Department::where('name','ยานพาหนะ')->first();
        $driver=Employee::where('department_id',$department->id)->orderBy('name','desc')->get();
        return response()->json($driver);
    }
}
