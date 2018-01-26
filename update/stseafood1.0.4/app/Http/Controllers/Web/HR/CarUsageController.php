<?php

namespace App\Http\Controllers\Web\HR;

use App\Http\Controllers\WebService\DateController;
use App\Models\HumanResource\Car;
use App\Models\HumanResource\CarUsage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class CarUsageController extends Controller
{
    public function index(){
        return view('site.hr.index');
    }
    //Index By Month
    public function indexByMonth(){
        $date=Carbon::today()->format('Y-m');
        $dateInput=Carbon::createFromFormat('Y-m',$date);
        $month=$dateInput->month;
        $year=$dateInput->year;
        $cars=Car::with('carType')->get()->groupBy('car_type_id');
        $th_months = DateController::getFullThaiMonths();
        $thai_month = "";
        foreach ($th_months as $key => $value) {
            if ($month == $key + 1) {
                $thai_month = $value;
            }
        }
//        dd($cars);
        return view('site.hr.by_month.index')->with(
            [
                'cars'=>$cars,
                'date'=>$date,
                'thai_month'=>$thai_month,
                'year'=>$year
            ]);
    }

    //Index By Year
    public function indexByYear(){
        $date=Carbon::today()->format('Y');
        $cars=Car::with('carType')->get()->groupBy('car_type_id');
        return view('site.hr.by_year.index')->with(
            [
                'cars'=>$cars,
                'date'=>$date
            ]);
    }
    
    //Get By Month
    public function getByMonth(Request $request){
        $dateInput=Carbon::createFromFormat('Y-m',$request->input('date'));
        $month=$dateInput->month;
        $year=$dateInput->year;
        $th_months = DateController::getFullThaiMonths();
        $thai_month = "";
        foreach ($th_months as $key => $value) {
            if ($month == $key + 1) {
                $thai_month = $value;
            }
        }
        $cars=Car::with('carType')->get()->groupBy('car_type_id');
        return view('site.hr.by_month.index')->with(
            [
                'cars'=>$cars,
                'date'=>$request->input('date'),
                'thai_month'=>$thai_month,
                'year'=>$year
            ]);
    }
    //Get By Year
    //Get By Month
    public function getByYear(Request $request){
        $cars=Car::with('carType')->get()->groupBy('car_type_id');
        return view('site.hr.by_year.index')->with(
            [
                'cars'=>$cars,
                'date'=>$request->input('date')
            ]);
    }

    //Get Excel By Month
    public function getExcelByMonth($date,$car_id){
        $dateInput=Carbon::createFromFormat('Y-m',$date);
        $month=$dateInput->month;
        $year=$dateInput->year;
        $th_months = DateController::getFullThaiMonths();
        $thai_month = "";
        foreach ($th_months as $key => $value) {
            if ($month == $key + 1) {
                $thai_month = $value;
            }
        }
        $dateString='เดือน '.$thai_month.' '.$year;
        $results=$carUsage = CarUsage::with('car.carType')->where('car_id',$car_id)
            ->where('car_access_status_id', 3)
            ->whereYear('date_arrival', $year)
            ->whereMonth('date_arrival', $month)
            ->orderBy('date_arrival', 'desc')
            ->orderBy('time_arrival', 'desc')
            ->get();
//        dd($month);
        Excel::create('report', function ($excel) use ($results,$dateString) {
            //Table Header
            $excel->sheet('sheet_1', function ($sheet) use ($results,$dateString) {
                $sheet->setAllBorders('thin');
                $sheet->setWidth('B', 10);
                $sheet = $this->setTable($sheet, $results,$dateString);

            });
        })->export('xls');
    }

    //Get Excel By Year
    public function getExcelByYear($date,$car_id){
        $dateInput=Carbon::createFromFormat('Y',$date);
        $year=$dateInput->year;
        $dateString='ปี '.$year;
        $results=$carUsage = CarUsage::with('car.carType')->where('car_id',$car_id)
            ->where('car_access_status_id', 3)
            ->whereYear('date_arrival', $year)
            ->orderBy('date_arrival', 'asc')
            ->orderBy('time_arrival', 'asc')
            ->get();
//        dd($car_usage);
//        $result=Car
        Excel::create('report', function ($excel) use ($results,$dateString) {
            //Table Header
            $excel->sheet('sheet_1', function ($sheet) use ($results,$dateString) {
                $sheet->setAllBorders('thin');
                $sheet->setWidth('B', 10);
                $sheet = $this->setTable($sheet, $results,$dateString);

            });
        })->export('xls');
    }
    public function setTable($sheet,$results,$dateString){
//        dd($results);
        $sumDistance=number_format($results->sum('distance'),2);
        $sumGasFill=number_format($results->sum('gas_fill'),2);
        $sumGasTotalPrice=number_format($results->sum('gas_total_price'),2);
        $avgDistanceLitre=number_format($results->avg('distance_per_litre'),2);
        $avgGasUnitPrice=number_format($results->avg('gas_unit_price'),2);
        $avgPriceDistance=number_format($results->avg('price_per_distance'),2);
        $sheet->setWidth(array(
            'A'     =>  15,
            'B'     =>  15,
            'C'     =>  15,
            'D'     =>  15,
            'E'     =>  15,
            'F'     =>  15,
            'G'     =>  15,
            'H'     =>  15,
            'I'     =>  15,
            'J'     =>  15,
            'K'     => 15
        ));
        $sheet->setOrientation('landscape');
        $sheet->setPageMargin(array(
            0.25, 0.30, 0.25, 0.30
        ));
        $sheet->mergeCells('A1:K1');
        if($results->count()>0){
            $sheet->cell('A1','ประจำ: '.$dateString .' รถ: '.$results[0]->car->carType->name. ' หมายเลข: '
                .$results[0]->car->car_number.' ป้ายทะเบียน: '.$results[0]->car->plate_number);
        }else{
            $sheet->cell('A1','ประจำ: '.$dateString);
        }
        $sheet->cell('A2','วดป.');
        $sheet->cell('B2','เลขไมล์');
        $sheet->cell('C2','กม.');
        $sheet->cell('D2','จำนวนลิตร');
        $sheet->cell('E2','ก.ม./ลิตร');
        $sheet->cell('F2','@');
        $sheet->cell('G2','จำนวนเงิน');
        $sheet->cell('H2','บาท/กม.');
        $sheet->cell('I2','เวลา');
        $sheet->cell('J2','สถานที่');
        $sheet->cell('K2','ผู้เติม');

        $sheet->getStyle('A1:K2')
            ->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $row=3;
        foreach ($results as $result){
            $sheet->cell('A'.$row,$result->date_arrival);
            $sheet->cell('B'.$row,$result->mile_end);
            $sheet->cell('C'.$row,$result->distance);
            $sheet->cell('D'.$row,$result->gas_fill);
            $sheet->cell('E'.$row,$result->distance_per_litre);
            $sheet->cell('F'.$row,$result->gas_unit_price);
            $sheet->cell('G'.$row,$result->gas_total_price);
            $sheet->cell('H'.$row,$result->price_per_distance);
            $sheet->cell('I'.$row,$result->gas_fill_time);
            $sheet->cell('J'.$row,$result->gas_station);
            $sheet->cell('K'.$row,$result->gas_fill_by);

            $sheet->getStyle('A'.$row.':K'.$row)
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $row++;
        }
        //Sum Row
        $sheet->cell('A'.$row,'รวม');
        $sheet->cell('C'.$row,$sumDistance);
        $sheet->cell('D'.$row,$sumGasFill);
        $sheet->cell('G'.$row,$sumGasTotalPrice);
        $sheet->cells('A'.$row.':K'.$row,function($cells){
            $cells->setBackground('#CDDDAC');
        });
        //Avg Row
        $sheet->cell('A'.($row+1),'ค่าเฉลี่ย');
        $sheet->cell('E'.($row+1),$avgDistanceLitre);
        $sheet->cell('F'.($row+1),$avgGasUnitPrice);
        $sheet->cell('H'.($row+1),$avgPriceDistance);
        $sheet->getStyle('A'.($row).':K'.($row+1))
            ->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->cells('A'.($row+1).':K'.($row+1),function($cells){
            $cells->setBackground('#A5D5E3');
        });
        return $sheet;
    }


}
