<?php

namespace App\Http\Controllers\Api\HumanResource;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DriverController extends Controller
{
    public function getAllDriver(){
        //Departments ยานพาหนะ อาจจะอยู่ในฝ่ายที่แตกต่างกัน เลยทำให้สามารถ select มาได้หลาย ID
        $departmentIDs=Department::where('name','ยานพาหนะ')->get()->pluck('id');
        //ดังนั้นเลยใช้ whereIn ในการหา พนักงานที่เป็นคนขับรถ
        $driver=Employee::whereIn('department_id',$departmentIDs)->orderBy('name','asc')->get();
        return response()->json($driver);
    }
}
