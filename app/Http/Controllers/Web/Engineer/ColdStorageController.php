<?php

namespace App\Http\Controllers\Web\Engineer;

use App\Http\Controllers\Api\Eng\Result\ColdStorageResultController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ColdStorageController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->format('Y-m-d');
        return view('site.engineer.cold_storage.index')->with('today', $today);
    }

    public function getRecordByDate(Request $request)
    {
        return view('site.engineer.cold_storage.index')->with('today', $request->input('date'));
    }

    public function getExcel($date)
    {
        $cold_storage = new ColdStorageResultController();
        $results = $cold_storage->getResultByDate($date)->getData();
//        dd($results);
        Excel::create('report', function ($excel) use ($results) {
            //Table Header
            $excel->sheet('sheet_1', function ($sheet) use ($results) {
                $sheet->setAllBorders('thin');
                //Sheet Setting
                $sheet->setOrientation('landscape');
                $sheet->setPageMargin(array(
                    0.25, 0.30, 0.25, 0.30
                ));
                $sheet = $this->setTable($sheet, $results);
            });
        })->export('xls');
    }

    public function setTable($sheet, $results)
    {
//        dd($results);
        $sheet->mergeCells('A1:F1');
        $sheet->cell('A1', 'Cold storage temp. Data logger');
        $sheet->mergeCells('B2:F4');
        $sheet->mergeCells('A2:A4');
        $sheet->cell('A2', 'วันที่');
        $sheet->cell('B2', $results->date);


        $sheet->cell('A5', 'Room');
        $sheet->cell('B5', 'Unit');
        $sheet->mergeCells('C5:D5');
        $sheet->cell('C5', 'Value');
        $sheet->cell('E5', 'At');
        //Room1
        $sheet->cell('A6', 'Room 1');
        $sheet->cell('B6', '(C)');
        //max
        $sheet->cell('C6', 'Max =');
        $sheet->cell('D6', $results->cs1_max->cs1_rm);
        $sheet->cell('E6', $results->cs1_max->date);
        $sheet->cell('F6', $results->cs1_max->time_record);
        //min
        $sheet->cell('C7', 'Min =');
        $sheet->cell('D7', $results->cs1_min->cs1_rm);
        $sheet->cell('E7', $results->cs1_min->date);
        $sheet->cell('F7', $results->cs1_min->time_record);
        //Room2
        $sheet->cell('A8', 'Room 2');
        $sheet->cell('B8', '(C)');
        //max
        $sheet->cell('C8', 'Max =');
        $sheet->cell('D8', $results->cs2_max->cs2_rm);
        $sheet->cell('E8', $results->cs2_max->date);
        $sheet->cell('F8', $results->cs2_max->time_record);
        //min
        $sheet->cell('C9', 'Min =');
        $sheet->cell('D9', $results->cs2_min->cs2_rm);
        $sheet->cell('E9', $results->cs2_min->date);
        $sheet->cell('F9', $results->cs2_min->time_record);
        $sheet->mergeCells('A10:F10');

        //Cold Table
        $sheet->cell('A11', 'Date');
        $sheet->cell('B11', 'Time');
        $sheet->mergeCells('C11:D11');
        $sheet->cell('C11', 'Room1');
        $sheet->mergeCells('E11:F11');
        $sheet->cell('E11', 'Room2');

        $sheet->getStyle('A1:F11')
            ->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $row = 12;
        foreach ($results->data as $result) {
            $sheet->mergeCells('C'.$row.':D'.$row);
            $sheet->mergeCells('E'.$row.':F'.$row);
            $sheet->cell('A' . $row, $result->date);
            $sheet->cell('B' . $row, $result->time_record);
            $sheet->cell('C' . $row, $result->cs1_rm);
            $sheet->cell('E' . $row, $result->cs2_rm);
            $sheet->getStyle('A' . $row . ':F' . $row)
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $row++;
        }
        $rowHeight = collect([]);
        for ($j = 1; $j <= $row; $j++) {
            $rowHeight->push(25);
        }
        $sheet->setHeight($rowHeight->toArray());
    }
}
