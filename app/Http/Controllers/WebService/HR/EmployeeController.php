<?php

namespace App\Http\Controllers\WebService\HR;

use App\Models\Department;
use App\Models\Division;
use App\Models\Employee;
use App\Models\Rank;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    //Use importEmployee method after set division and department method
    public function importEmployee()
    {
        $divisions = Division::all();
        $departments = Department::all();
        $ranks = Rank::all();
        $newEmployees = collect();
        $divisionDepartments = collect();
        $salary_type_id='';
//        $excels = Excel::load('public/documents/employees.xlsx', function ($reader) {
        $sheets = Excel::selectSheets('monthly', 'daily')->load('public/documents/employee24-1-61editVersion.xlsx', function ($reader) {
        })->get(['em_id', 'prefix_name', 'name', 'lastname', 'division_id', 'department_id', 'rank_id', 'salary_type_id'])
            ->all();

        //***Important use trim to remove white space
        foreach ($sheets as $sheet) {
            if($sheet->getTitle()=='monthly'){
                $salary_type_id=1; // 1 is monthly
            }else{
                $salary_type_id=2; // 2 is daily
            }
            foreach ($sheet as $excel) {
                //Find division id from database
                $division = $divisions->where('name', trim($excel['division_id']))->first();
                //Find Department id from database
                $department = $departments->where('name', trim($excel['department_id']))->first();
                //Find Rank id from database
                $rank = $ranks->where('name', $excel['rank_id'])->first();
                //set division_id from database
                $excel['division_id'] = $division ? $division->id : 0;
                //set department_id from database
                $excel['department_id'] = $department ? $department->id : 0;
                //set rank_id from database instead of rank_name
                $excel['rank_id'] = $rank ? $rank->id : 0;
                //set salary_type_id
                $excel['salary_type_id']=$salary_type_id;

                //set division_id to department model
                $divisionDepartment = [
                    'division_id' => $division->id,
                    'name' => $department->name,
                    'department_id' => $department->id
                ];
                //push it from above
                $divisionDepartments->push($divisionDepartment);
//                dd($excel->all());
                $newEmployees->push($excel->all());
            }
//            dd($newEmployees);
        }
//        dd($newEmployees);
        //Remove duplicate data with unique method
        $divisionDepartments = $divisionDepartments->unique();

        DB::transaction(function () use ($newEmployees, $divisionDepartments) {
            Employee::truncate();
            $addEmployee = new Employee();
            $addEmployee->insert($newEmployees->toArray());
            foreach ($divisionDepartments as $divisionDepartment) {
                Department::where('id', $divisionDepartment['department_id'])
                    ->update([
                        'division_id' => $divisionDepartment['division_id']
                    ]);
            }
        });
    }

    //Import Daily Employee
    public function importDailyEmployees()
    {

    }

    public function importDepartments()
    {
        $newDepartments = collect();
        $excels = Excel::selectSheets('all_department')
            ->load('public/documents/employee24-1-61editVersion.xlsx', function ($reader) {
            })->get(['name'])->all();
        foreach ($excels as $excel) {
            $newDepartments->push($excel->all());
        }
        DB::transaction(function () use ($newDepartments) {
            Department::truncate();
            $addDepartments = new Department();
            $addDepartments->insert($newDepartments->toArray());
        });
    }

    public function importRanks()
    {
        $newRanks = collect([]);
        //Use selectSheets method to select only ranks sheet
        $excels = Excel::selectSheets('ranks')->load('public/documents/employee24-1-61editVersion.xlsx', function ($reader) {
        })->get();
//        dd($excels);
        foreach ($excels as $excel) {
            $newRanks->push($excel->all());
        }
        DB::transaction(function () use ($newRanks) {
            Rank::truncate();
            $addRanks = new Rank();
            $addRanks->insert($newRanks->toArray());
        });
        return "Success";
    }

}
