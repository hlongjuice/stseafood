<?php

namespace App\Http\Controllers\Web\Engineer;

use App\Http\Controllers\Api\Eng\Result\WaterUsageResultController;
use App\Http\Controllers\WebService\DateController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class WaterUsageController extends Controller
{
    private $row = 5;

    public function index()
    {
        $th_months = DateController::getFullThaiMonths();
        $thai_month = "";
        $month = Carbon::today()->month;
        foreach ($th_months as $key => $value) {
            if ($month == $key + 1) {
                $thai_month = $value;
            }
        }
        $year = Carbon::today()->year;
        $waterUsageResult = new WaterUsageResultController();
        $results = $waterUsageResult->calculateResult($month, $year);
        $hasRecords = $results['sumRow']['used']->filter();
        if ($hasRecords->count() > 0) {
            return view('site.engineer.water_usage.index')->with([
                'month' => $month,
                'year' => $year,
                'thai_month' => $thai_month
            ]);
        }
        return view('site.engineer.water_usage.index')->with([
            'month' => null,
            'year' => null,
            'thai_month' => null
        ]);
    }

    public function getRecordByMonth(Request $request)
    {
        $th_months = DateController::getFullThaiMonths();
        $thai_month = "";
        $dateInput = Carbon::createFromFormat('Y-m', $request->input('date'));
        $month = $dateInput->month;
        $year = $dateInput->year;
        foreach ($th_months as $key => $value) {
            if ($month == $key + 1) {
                $thai_month = $value;
            }
        }
        $waterUsageResult = new WaterUsageResultController();
        $results = $waterUsageResult->calculateResult($month, $year);
        $hasRecords = $results['sumRow']['used']->filter();
        if ($hasRecords->count() > 0) {
            return view('site.engineer.water_usage.index')->with([
                'month' => $month,
                'year' => $year,
                'thai_month' => $thai_month
            ]);
        }
        return view('site.engineer.water_usage.index')->with([
            'month' => null,
            'year' => null,
            'thai_month' => null
        ]);
//        return redirect('engineer.export.index')->with('result'.$record);
    }

    public function getExcel($month, $year)
    {
        $th_months = DateController::getFullThaiMonths();
        $thai_month = "";
        foreach ($th_months as $key => $value) {
            if ($month == $key + 1) {
                $thai_month = $value;
            }
        }
        $waterUsageResult = new WaterUsageResultController();
        $results = $waterUsageResult->calculateResult($month, $year);
        $results->put('month', $month);
        $results->put('year', $year);
        $results->put('thai_month', $thai_month);
//        dd($results);

        Excel::create('report', function ($excel) use ($results) {
            //Table Header
            $excel->sheet('sheet_1', function ($sheet) use ($results) {
                $sheet->setAllBorders('thin');
                $sheet->setWidth('B', 10);
                $sheet = $this->setTableHeader($sheet, $results);
                $sheet = $this->setTableContent($sheet, $results);
                $sheet = $this->setTableFooterSum($sheet, $results['sumRow']);
                $sheet = $this->setTableFooterAvg($sheet, $results['avgRow']);

            });
        })->export('xls');
    }

    //Table Header
    public function setTableHeader($sheet, $results)
    {
        //Sheet Setting
        $sheet->setOrientation('landscape');
        $sheet->setPageMargin(array(
            0.25, 0.30, 0.25, 0.30
        ));
        $sheet->mergeCells('A1:AI1');
        $sheet->cell('A1', 'สุรปการใช้น้ำ ' . $results['thai_month'] . ' ' . $results['year']);
        //Date
        $sheet->setMergeColumn(array(
            'columns' => array('A'),
            'rows' => array(
                array(2, 4),
            )
        ));
        $sheet->cell('A2', 'วันที่');
        //Pwa
        $sheet->setMergeColumn(array(
            'columns' => array('B'),
            'rows' => array(
                array(2, 3),
            )
        ));
        $sheet->cell('B2', 'สายนอก');
        $sheet->cell('B4', 'กปภ.');
        //Water Filtration
        $sheet->mergeCells('C2:G2');
        $sheet->cell('C2', 'โรงกรองน้ำ');
        $sheet->cell('C3', 'Plant 1');
        $sheet->cell('D3', 'Plant 2');
        $sheet->cell('E3', 'P 1+2');
        $sheet->cell('F3', '210');
        $sheet->cell('G3', 'ผลต่าง');

        //Main
        $sheet->cell('H3', 'Main');
        $sheet->cell('H4', '3"');

        //Machine
        $sheet->mergeCells('I2:S2');
        $sheet->cell('I2', 'เครื่องจักร');
        $sheet->cell('I3', 'Cond.2');
        $sheet->cell('I4', 'Ecl750');
        $sheet->cell('J3', 'Cond.3');
        $sheet->cell('J4', 'Eco 7');
        $sheet->cell('K3', 'Cond.5');
        $sheet->cell('K4', 'Ecl 300');
        $sheet->cell('L3', 'Cond.6');
        $sheet->cell('L4', 'Ecs 500');
        $sheet->cell('M3', 'Cond.7');
        $sheet->cell('N3', 'Cond.8');
        $sheet->cell('O3', 'รวม');
        $sheet->cell('O4', 'Cond.');
        $sheet->cell('P3', 'Boiler');
        $sheet->cell('P4', '1');
        $sheet->cell('Q3', 'Boiler');
        $sheet->cell('Q4', '2');
        $sheet->cell('R3', 'รวม');
        $sheet->cell('R4', 'Boiler');
        $sheet->cell('S3', 'ผลต่าง');

        //Production Line
        $sheet->mergeCells('T2:AA2');
        $sheet->cell('T2', 'Line ผลิต');
        $sheet->cell('T3', 'ผลิต 1');
        $sheet->cell('T4', '3"');
        $sheet->cell('U3', 'ผลิต 2');
        $sheet->cell('U4', '3"');
        $sheet->cell('V3', '20T');
        $sheet->cell('V4', 'กุ้งต้ม');
        $sheet->cell('W3', '20T');
        $sheet->cell('W4', 'ซูชิ');
        $sheet->cell('X3', '20T');
        $sheet->cell('X4', 'Conv.');
        $sheet->cell('Y3', 'รวม');
        $sheet->cell('Y4', 'ICE');
        $sheet->cell('Z3', 'Chiller');
        $sheet->cell('AA3', 'ผลิต');
        $sheet->cell('AA4', 'ใช้');

        //Room
        $sheet->mergeCells('AB2:AG2');
        $sheet->cell('AB2', 'ห้องน้ำ-บ้านพัก');
        $sheet->cell('AB3', 'M-15');
        $sheet->cell('AC3', 'M-17');
        $sheet->cell('AC4', 'สองชั้น');
        $sheet->cell('AD3', 'M-18');
        $sheet->cell('AD4', 'ซักผ้า');
        $sheet->cell('AE3', 'M-19');
        $sheet->cell('AE4', 'รายเดือน');
        $sheet->cell('AF3', 'M-20');
        $sheet->cell('AF4', 'Lab');
        $sheet->cell('AG3', 'M-21');
        $sheet->cell('AG4', 'ผสมเกลือ');

        //Total
        $sheet->cell('AH2', 'รวม');
        $sheet->cell('AH3', 'ผลิตใช้');
        $sheet->cell('AH4', 'ลบ.ม.');
        $sheet->cell('AI2', 'รวม');
        $sheet->cell('AI3', 'ทั้งหมด');
        $sheet->cell('AI4', 'ลบ.ม.');

        $sheet->getStyle('A1:AI4')
            ->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        return $sheet;
    }

    //Table Content
    public function setTableContent($sheet, $results)
    {
        foreach ($results['data'] as $index => $result) {
            $sheet->cell('A' . $this->row, $index + 1);
            //Pwa
            $sheet->cell('B' . $this->row, $result['used']['ws_outside']['m_pwa_used']);
            //Water Filtration
//            $sheet->cell('C'.$i,$result['used']['water_filtration']['p1_mm1_used']);
            $sheet->cell('C' . $this->row, "0");
            //$sheet->cell('C'.$i,true);
            $sheet->cell('D' . $this->row, $result['used']['water_filtration']['p2_mm2_used']);
            $sheet->cell('E' . $this->row, $result['sumUsed']['sumP1P2']);
            $sheet->cell('F' . $this->row, $result['used']['tank_210']['mm3_used']);
            $sheet->cell('G' . $this->row, $result['sumUsed']['sumP1P2Minus210']);

            //Main
            $sheet->cell('H' . $this->row, $result['used']['water_meter']['mm_6_used']);

            //Machine
            $sheet->cell('I' . $this->row, $result['used']['condens']['con2_w_meter_used']);
            $sheet->cell('J' . $this->row, $result['used']['condens']['con3_w_meter_used']);
            $sheet->cell('K' . $this->row, $result['used']['condens']['con5_meter_m5_used']);
            $sheet->cell('L' . $this->row, $result['used']['condens']['con6_meter_m6_used']);
            $sheet->cell('M' . $this->row, $result['used']['condens']['con7_meter_m7_used']);
            $sheet->cell('N' . $this->row, $result['used']['condens']['con8_w_meter_used']);
            $sheet->cell('O' . $this->row, $result['sumUsed']['sumCondens']);
            $sheet->cell('P' . $this->row, $result['used']['boiler']['boiler1_meter_used']);
            $sheet->cell('Q' . $this->row, $result['used']['boiler']['boiler2_meter_used']);
            $sheet->cell('R' . $this->row, $result['sumUsed']['sumB1B2']);
            $sheet->cell('S' . $this->row, $result['sumUsed']['main3MinusCondBoiler']);

            //Production Line
            $sheet->cell('T' . $this->row, $result['used']['water_meter']['mm_4_used']);
            $sheet->cell('U' . $this->row, $result['used']['water_meter']['mm_5_used']);
            $sheet->cell('V' . $this->row, $result['used']['ice_maker']['freezer1_m12_used']);
            $sheet->cell('W' . $this->row, $result['used']['ice_maker']['freezer2_m2_used']);
            $sheet->cell('X' . $this->row, $result['used']['ice_maker']['freezer3_m14_used']);
            $sheet->cell('Y' . $this->row, $result['sumUsed']['sumIce']);
            $sheet->cell('Z' . $this->row, $result['used']['water_cooler']['ripple_m13_used']);
            $sheet->cell('AA' . $this->row, $result['sumUsed']['sumWaterMeterMain3']);

            //Room
            $sheet->cell('AB' . $this->row, $result['used']['ws_outside']['m_15_used']);
            $sheet->cell('AC' . $this->row, $result['used']['ws_outside']['m_17_used']);
            $sheet->cell('AD' . $this->row, $result['used']['ws_outside']['m_18_used']);
            $sheet->cell('AE' . $this->row, $result['used']['ws_outside']['m_19_used']);
            $sheet->cell('AF' . $this->row, $result['used']['ws_outside']['m_20_used']);
            $sheet->cell('AG' . $this->row, $result['used']['ws_outside']['m_21_used']);

            //Total
            $sheet->cell('AH' . $this->row, $result['sumUsed']['sumBoilerMain3M21']);
            $sheet->cell('AI' . $this->row, $result['sumUsed']['sumTotal']);
            $sheet->getStyle('A' . $this->row . ':AI' . ($this->row))
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->row++;
        }
        $rowHeight = collect([]);
        for ($j = 1; $j <= $this->row; $j++) {
            $rowHeight->push(25);
        }
        $sheet->setHeight($rowHeight->toArray());
        return $sheet;
    }

    //Table Footer Sum
    public function setTableFooterSum($sheet, $result)
    {
        $sheet->cell('A' . $this->row, 'ผลรวม');
        //Pwa
        $sheet->cell('B' . $this->row, $result['used']['m_pwa_used']);
        //Water Filtration
        $sheet->cell('C' . $this->row, $result['used']['p1_mm1_used']);
        $sheet->cell('D' . $this->row, $result['used']['p2_mm2_used']);
        $sheet->cell('E' . $this->row, $result['sumUsed']['sumP1P2']);
        $sheet->cell('F' . $this->row, $result['used']['mm3_used']);
        $sheet->cell('G' . $this->row, $result['sumUsed']['sumP1P2Minus210']);

        //Main
        $sheet->cell('H' . $this->row, $result['used']['mm_6_used']);

        //Machine
        $sheet->cell('I' . $this->row, $result['used']['con2_w_meter_used']);
        $sheet->cell('J' . $this->row, $result['used']['con3_w_meter_used']);
        $sheet->cell('K' . $this->row, $result['used']['con5_meter_m5_used']);
        $sheet->cell('L' . $this->row, $result['used']['con6_meter_m6_used']);
        $sheet->cell('M' . $this->row, $result['used']['con7_meter_m7_used']);
        $sheet->cell('N' . $this->row, $result['used']['con8_w_meter_used']);
        $sheet->cell('O' . $this->row, $result['sumUsed']['sumCondens']);
        $sheet->cell('P' . $this->row, $result['used']['boiler1_meter_used']);
        $sheet->cell('Q' . $this->row, $result['used']['boiler2_meter_used']);
        $sheet->cell('R' . $this->row, $result['sumUsed']['sumB1B2']);
        $sheet->cell('S' . $this->row, $result['sumUsed']['main3MinusCondBoiler']);

        //Production Line
        $sheet->cell('T' . $this->row, $result['used']['mm_4_used']);
        $sheet->cell('U' . $this->row, $result['used']['mm_5_used']);
        $sheet->cell('V' . $this->row, $result['used']['freezer1_m12_used']);
        $sheet->cell('W' . $this->row, $result['used']['freezer2_m2_used']);
        $sheet->cell('X' . $this->row, $result['used']['freezer3_m14_used']);
        $sheet->cell('Y' . $this->row, $result['sumUsed']['sumIce']);
        $sheet->cell('Z' . $this->row, $result['used']['ripple_m13_used']);
        $sheet->cell('AA' . $this->row, $result['sumUsed']['sumWaterMeterMain3']);

        //Room
        $sheet->cell('AB' . $this->row, $result['used']['m_15_used']);
        $sheet->cell('AC' . $this->row, $result['used']['m_17_used']);
        $sheet->cell('AD' . $this->row, $result['used']['m_18_used']);
        $sheet->cell('AE' . $this->row, $result['used']['m_19_used']);
        $sheet->cell('AF' . $this->row, $result['used']['m_20_used']);
        $sheet->cell('AG' . $this->row, $result['used']['m_21_used']);

        //Total
        $sheet->cell('AH' . $this->row, $result['sumUsed']['sumBoilerMain3M21']);
        $sheet->cell('AI' . $this->row, $result['sumUsed']['sumTotal']);
        $sheet->getStyle('A' . $this->row . ':AI' . ($this->row))
            ->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->setHeight($this->row, 40);
        return $sheet;
    }

    //Table Footer Avg
    public function setTableFooterAvg($sheet, $result)
    {
        $sheet->cell('A' . ($this->row + 1), 'ค่าเฉลี่ย');
        //Pwa
        $sheet->cell('B' . ($this->row + 1), $result['used']['m_pwa_used']);
        //Water Filtration
        $sheet->cell('C' . ($this->row + 1), $result['used']['p1_mm1_used']);
        $sheet->cell('D' . ($this->row + 1), $result['used']['p2_mm2_used']);
        $sheet->cell('E' . ($this->row + 1), $result['sumUsed']['sumP1P2']);
        $sheet->cell('F' . ($this->row + 1), $result['used']['mm3_used']);
        $sheet->cell('G' . ($this->row + 1), $result['sumUsed']['sumP1P2Minus210']);

        //Main
        $sheet->cell('H' . ($this->row + 1), $result['used']['mm_6_used']);

        //Machine
        $sheet->cell('I' . ($this->row + 1), $result['used']['con2_w_meter_used']);
        $sheet->cell('J' . ($this->row + 1), $result['used']['con3_w_meter_used']);
        $sheet->cell('K' . ($this->row + 1), $result['used']['con5_meter_m5_used']);
        $sheet->cell('L' . ($this->row + 1), $result['used']['con6_meter_m6_used']);
        $sheet->cell('M' . ($this->row + 1), $result['used']['con7_meter_m7_used']);
        $sheet->cell('N' . ($this->row + 1), $result['used']['con8_w_meter_used']);
        $sheet->cell('O' . ($this->row + 1), $result['sumUsed']['sumCondens']);
        $sheet->cell('P' . ($this->row + 1), $result['used']['boiler1_meter_used']);
        $sheet->cell('Q' . ($this->row + 1), $result['used']['boiler2_meter_used']);
        $sheet->cell('R' . ($this->row + 1), $result['sumUsed']['sumB1B2']);
        $sheet->cell('S' . ($this->row + 1), $result['sumUsed']['main3MinusCondBoiler']);

        //Production Line
        $sheet->cell('T' . ($this->row + 1), $result['used']['mm_4_used']);
        $sheet->cell('U' . ($this->row + 1), $result['used']['mm_5_used']);
        $sheet->cell('V' . ($this->row + 1), $result['used']['freezer1_m12_used']);
        $sheet->cell('W' . ($this->row + 1), $result['used']['freezer2_m2_used']);
        $sheet->cell('X' . ($this->row + 1), $result['used']['freezer3_m14_used']);
        $sheet->cell('Y' . ($this->row + 1), $result['sumUsed']['sumIce']);
        $sheet->cell('Z' . ($this->row + 1), $result['used']['ripple_m13_used']);
        $sheet->cell('AA' . ($this->row + 1), $result['sumUsed']['sumWaterMeterMain3']);

        //Room
        $sheet->cell('AB' . ($this->row + 1), $result['used']['m_15_used']);
        $sheet->cell('AC' . ($this->row + 1), $result['used']['m_17_used']);
        $sheet->cell('AD' . ($this->row + 1), $result['used']['m_18_used']);
        $sheet->cell('AE' . ($this->row + 1), $result['used']['m_19_used']);
        $sheet->cell('AF' . ($this->row + 1), $result['used']['m_20_used']);
        $sheet->cell('AG' . ($this->row + 1), $result['used']['m_21_used']);

        //Total
        $sheet->cell('AH' . ($this->row + 1), $result['sumUsed']['sumBoilerMain3M21']);
        $sheet->cell('AI' . ($this->row + 1), $result['sumUsed']['sumTotal']);
        $sheet->getStyle('A' . ($this->row + 1) . ':AI' . (($this->row + 1)))
            ->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->setHeight($this->row+1, 40);
        return $sheet;
    }
}
