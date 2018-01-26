<?php

namespace App\Http\Controllers\Web\Production;

use App\Http\Controllers\WebService\DateController;
use App\Models\Production\ProductionDate;
use App\Models\Production\ProductionWork;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class WorkController extends Controller
{
    public function index()
    {
        $date = Carbon::today()->format('Y-m-d');
        $dateInput = Carbon::createFromFormat('Y-m-d', $date);
        $day = $dateInput->day;
        $month = $dateInput->month;
        $year = $dateInput->year;
        $th_months = DateController::getFullThaiMonths();
        $thai_month = "";
        foreach ($th_months as $key => $value) {
            if ($month == $key + 1) {
                $thai_month = $value;
            }
        }
        $result = ProductionDate::with('productionDateTime.productionWork.productionWorkPerformance',
            'productionDateTime.productionWork.productionActivity',
            'productionDateTime.productionWork.productionShrimpType',
            'productionDateTime.productionWork.productionShrimpSize'
        )->where('date', $date)->first();
//        dd($result);
        return view('site.production.index')->with([
            'result'=>$result,
            'day' => $day,
            'thai_month' => $thai_month,
            'year' => $year
        ]);
    }

    //Get By Date
    public function getRecordByDate(Request $request)
    {
        $dateInput = Carbon::createFromFormat('Y-m-d', $request->input('date'));
        $day = $dateInput->day;
        $month = $dateInput->month;
        $year = $dateInput->year;
        $th_months = DateController::getFullThaiMonths();
        $thai_month = "";
        foreach ($th_months as $key => $value) {
            if ($month == $key + 1) {
                $thai_month = $value;
            }
        }
        $result = ProductionDate::with('productionDateTime.productionWork.productionWorkPerformance',
            'productionDateTime.productionWork.productionActivity',
            'productionDateTime.productionWork.productionShrimpType',
            'productionDateTime.productionWork.productionShrimpSize'
        )->where('date', $request->input('date'))->first();
//        dd($results->toArray());
        return view('site.production.index')->with([
            'result'=>$result,
            'day' => $day,
            'thai_month' => $thai_month,
            'year' => $year
        ]);
    }

    //Get Excel
    public function getExcel($id){
        $work=ProductionWork::with(
            'productionDateTime.productionDate',
            'productionWorkPerformance',
            'productionActivity',
            'productionShrimpType',
            'productionShrimpSize'
        )->where('id',$id)->first();
        $sumAllWork=number_format($work->productionWorkPerformance->sum('weight'));
        $avgAllWork=number_format($work->productionWorkPerformance->avg('weight'));
        $employeeWorks=$work->productionWorkPerformance->groupBy('em_id');
        $dateInput = Carbon::createFromFormat('Y-m-d',$work->productionDateTime->productionDate->date);
        $day = $dateInput->day;
        $month = $dateInput->month;
        $year = $dateInput->year;
        $th_months = DateController::getFullThaiMonths();
        $thai_month = "";
        foreach ($th_months as $key => $value) {
            if ($month == $key + 1) {
                $thai_month = $value;
            }
        }

        $time_start=Carbon::createFromFormat('H:i',$work->productionDateTime->time_start);
        $time_end=Carbon::createFromFormat('H:i',$work->p_time_end);
        $diffInMinute=$time_start->diffInMinutes($time_end);
        foreach ($employeeWorks as $employee =>$item){
            $item->em_id=$employee;
            $item->sumWeight=number_format($item->sum('weight'));
            $item->avgWeight=number_format($item->sum('weight')/$diffInMinute*60);
        }
        $results=collect([
            'sumAllWork'=>$sumAllWork,
            'avgAllWork'=>$avgAllWork,
            'employeeWorks'=>$employeeWorks,
            'day'=>$day,
            'thai_month'=>$thai_month,
            'year'=>$year,
            'time'=>$time_start->toTimeString().' - '.$time_end->toTimeString()
        ]);
        Excel::create('report', function ($excel) use ($results,$work) {
            //Table Header
            $excel->sheet('sheet_1', function ($sheet) use ($results,$work) {
                $sheet->setStyle(array(
                    'font' => array(
                        'size' => 15,
//                        'margin-left'=>2
//                        'bold' => true
                    )
                ));
                $sheet->setOrientation('landscape');
                $sheet->setPageMargin(array(
                    0.25, 0.30, 0.25, 0.30
                ));
                $sheet->setAllBorders('thin');
                $sheet = $this->setTable($sheet, $results,$work);

            });
        })->export('xls');

    }

    //Set Table
    public function setTable($sheet,$result,$work){
//        dd($work);
        $sheet->setWidth(array(
            'A'     =>  20,
            'B'     =>  20,
            'C'     =>  20,
        ));
        $sheet->mergeCells('A1:C1');
        $sheet->cell('A1','วันที่ : '.$result['day'].' '.$result['thai_month'].' '.$result['year']);
        $sheet->mergeCells('A2:C2');
        $sheet->cell('A2','ช่วงเวลา : '.$result['time']);
        $sheet->cell('A3',$work->productionActivity->name);
        $sheet->cell('B3',$work->productionShrimpType->name);
        $sheet->cell('C3',$work->productionShrimpSize->name);


        $sheet->cell('A4','รหัสพนักงาน');
        $sheet->cell('B4','น้ำหนักรวม');
        $sheet->cell('C4','น้ำหนักเฉลี่ย/ชม.');
        $sheet->getStyle('A1:C4')
            ->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $row=5;
        foreach ($result['employeeWorks'] as $employee){
            $sheet->cell('A'.$row,$employee->em_id);
            $sheet->cell('B'.$row,$employee->sumWeight);
            $sheet->cell('C'.$row,$employee->avgWeight);
            $sheet->getStyle('A'.$row.':C'.$row)
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $row++;
        }
        $sheet->cell('A'.($row+1),'รวม');
        $sheet->cell('B'.($row+1),$result['sumAllWork']);
        $sheet->getStyle('A'.($row+1).':C'.($row+1))
            ->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
    }

}
