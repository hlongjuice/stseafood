<?php

namespace App\Http\Controllers\Tester;

use App\Models\Division;
use App\Models\Employee;
use App\Models\FirstName;
use App\Models\LastName;
use App\Models\Production\ProductionEmployee;
use App\Models\SampleName;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class EmployeeController extends Controller
{
    public function addEmployee($number)
    {
        /*Get Random Name From FirstName Table*/
        $firstnames = SampleName::inRandomOrder()->take($number)->get();
        $lastnames = SampleName::inRandomOrder()->take($number)->get();

        $divisions = DB::table('divisions')->select('id')->get()->toArray();
        $division = $divisions[array_rand($divisions, 1)];

        echo $division->id;
        $newEmployees = [];

        /*Set Employee in Array*/
        $i = 0;
        foreach ($firstnames as $firstname) {
            $division = $divisions[array_rand($divisions, 1)];
            $newEmployees[] = [
                'name' => $firstname->name,
                'lastname' => $lastnames[$i]->name,
                'division_id' => $division->id,
                'salary_type_id'=>random_int(1,2)
            ];
            $i++;
        }
        /*Insert All Employee array to database*/
        Employee::insert($newEmployees);
    }
    /*Add Production Employee*/
    public function addProductionEmployee($number)
    {
        $employees = Employee::inRandomOrder()->take($number)->get();
        $productionEmployee = [];
        foreach ($employees as $employee) {
            $productionEmployee[] = [
                'em_id' => $employee->em_id,
                'group_id' => random_int(1, 10)
            ];
        }
        $addProductionEmployee = new ProductionEmployee();
        $addProductionEmployee->insert($productionEmployee);
    }



}
