<?php

namespace App\Http\Controllers\Api\HumanResource;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $employees=Employee::with('division')->get();
//        return  response()->json($employees);
    }

    /*Get All Employees*/
    public function getAllEmployee()
    {
        $employees = Employee::with('division', 'department', 'salaryType')
            ->orderBy('em_id')
//            ->get();
            ->paginate(200);
        return response()->json($employees);
    }
    /*Get All Employees without Page*/
    public function getAllEmployeeWithOutPage(){
        $employees = Employee::with('division', 'department', 'salaryType')
            ->orderBy('em_id')
            ->get();
        return response()->json($employees);
    }


    /*Get Division Employees*/
    public function getDivisionEmployee($divisionID)
    {
        $employees = Employee::with('division', 'department', 'salaryType')
            ->where('division_id', $divisionID)
            ->orderBy('em_id')
            ->paginate(100);
        return response()->json($employees);
    }

    /*Change Division Department*/
    public function changeDivisionDepartment(Request $request)
    {
        $employees = null;
        if ($request->has('department_id')) {
            $employees = Employee::whereIn('em_id', $request->input('employees'))
                ->update(['division_id' => $request->input('division_id'), 'department_id' => $request->input('department_id')]);
        } else {
            $employees = Employee::whereIn('em_id', $request->input('employees'))
                ->update(['division_id' => $request->input('division_id')]);
        }
        return response($employees);
    }

    /*Change Salary Type*/
    public function changeSalaryType(Request $request)
    {
        $employee = null;
        if ($request->has('employees') && $request->has('salary_type_id')) {
            $employee = Employee::whereIn('em_id', $request->input('employees'))
                ->update(['salary_type_id' => $request->input('salary_type_id')]);
        }
        return response($employee);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $employee = new Employee();
        $employee->em_id = $request->input('em_id');
        $employee->name = $request->input('name');
        $employee->lastname = $request->input('lastname');
        $employee->division_id = $request->input('division.id');
        $employee->created_by_user_id = $request->input('created_by_user_id');
        $employee->updated_by_user_id = $request->input('updated_by_user_id');
        $employee->save();
        return response()->json($employee);
    }

    /*Update*/
    public function update(Request $request)
    {
        $employee = Employee::where('em_id', $request->input('em_id'))->first();
        $employee->em_id = $request->input('em_id');
        $employee->name = $request->input('name');
        $employee->lastname = $request->input('lastname');
        $employee->division_id = $request->input('division_id');
        if ($request->has('department_id')) {
            $employee->department_id = $request->input('department_id');
        }
        $employee->salary_type_id = $request->input('salary_type_id');
        $employee->save();
        return response($employee);
    }

    /*Delete Employee*/
    public function delete(Request $request)
    {
        $employee = Employee::whereIn('em_id', $request->input('em_id'))->delete();
        return response($employee);
    }

}
