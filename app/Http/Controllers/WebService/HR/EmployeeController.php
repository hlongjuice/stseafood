<?php

namespace App\Http\Controllers\WebService\HR;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    public function importEmployee()
    {
        $newEmployees = collect();
//        $excels = Excel::load('public/documents/employees.xlsx', function ($reader) {
        $excels = Excel::load('public/documents/monthly_employees.xlsx', function ($reader) {
        })->get(['em_id', 'name', 'lastname', 'division_id', 'department_id', 'rank_id', 'salary_type_id'])->all();
//        })->get()->all();

        foreach ($excels as $excel) {
            $newEmployees->push($excel->all());
        }
//        dd($newEmployees);
//        dd($excels);
        DB::transaction(function () use ($newEmployees) {
            $addEmployee = new Employee();
            $addEmployee->insert($newEmployees->toArray());
        });

    }
}
