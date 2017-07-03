<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Models\Employee;

use Illuminate\Http\Request;

class ExcelController extends Controller
{
    public function index()
    {
         $employees=Employee::with('division')->get();
        $employeesArray=[];
        $i=0;
        foreach ($employees as $employee) {
          $employeesArray[$i]['ชื่อ']=$employee->name;
            $employeesArray[$i]['นามสกุล']=$employee->lastname;
            $employeesArray[$i]['แผนก']=$employee->division->name;
            $i++;
        }
        Excel::create('test', function ($excel) use ($employeesArray) {
            $excel->sheet('Sheet1', function ($sheet) use ($employeesArray) {
                $row=count($employeesArray)+1;
                $sheet->setFontFamily('garuda');
                $sheet->setBorder('A1:C'.$row, 'thin');
                $sheet->fromArray($employeesArray);
                $sheet->cells('A1:C1', function($cells) {
                    $cells->setBackground('#000000');
                    $cells->setFontColor('#ffffff');
                });
            });
        })->export('pdf');
    }
}
