<?php

namespace App\Http\Controllers\Web\QC;

use App\Models\QC\QcSupplierReceiving;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function index()
    {
        $date = Carbon::now()->toDateString();
        $records = QcSupplierReceiving::with('supplier')->whereDate('date', $date)
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('site.qc.index')->with('records', $records);
    }

    //Get Record By Date
    public function getRecordByDate(Request $request)
    {
        $records = QcSupplierReceiving::with('supplier')->whereDate('date', $request->input('date'))
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('site.qc.index')->with('records', $records);
    }

    public function getExcel($id)
    {
        $result = $this->calAllResult($id);
        Excel::create('report', function ($excel) use ($result) {
            //Table Header
            $excel->sheet('sheet_1', function ($sheet) use ($result) {
                $sheet->setAllBorders('thin');
                $sheet = $this->setTableHeader($sheet, $result);
                $sheet = $this->setTableContent($sheet, $result);

            });
            //Table Content
        })->export('xls');

    }

    //Table Header
    public function setTableHeader($sheet, $result)
    {
        $rowHeight=collect([]);
        for($i=1;$i<=8;$i++){
            $rowHeight->push(25);
        }
        $sheet->setHeight($rowHeight->toArray());
        //Sheet Setting
        $sheet->setOrientation('landscape');
        $sheet->setPageMargin(array(
            0.25, 0.30, 0.25, 0.30
        ));
        //Header Table
        $sheet->mergeCells('A1:V1');
        $sheet->cell('A1', function ($cell) {
            $cell->setValue('บริษัท สุราษฎร์ซีฟู๊ดส์ จำกัดบริษัท สุราษฎร์ซีฟู๊ดส์ จำกัด');

        });
        $sheet->mergeCells('A2:V2');
        $sheet->cell('A2', '21 ถนนเจริญลาภ ตำบลท่าข้าม อำเภอพุนพิน จังหวัดสุราษฎร์ธานี 84130');
        $sheet->mergeCells('A3:V3');
        $sheet->cell('A3', 'รายการตรวจรับกุ้งเป็น');
        //Date
        $sheet->cell('A4', 'วันที่');
        $sheet->mergeCells('B4:D4');
        $sheet->cell('B4', $result->date);
        //Supplier
        $sheet->mergeCells('E4:G4');
        $sheet->cell('E4', 'แหล่งที่มาของวัตถุดิบ');
        $sheet->mergeCells('H4:P4');
        $sheet->cell('H4', $result->supplier->name);
        //Pond
        $sheet->cell('Q4', 'บ่อ');
        $sheet->cell('R4', $result->pond);
        //Code
        $sheet->cell('S4', 'รหัสวัตถุดิบ');
        $sheet->mergeCells('T4:V4');
        $sheet->cell('T4', $result->code);
        //Details Header
        $sheet->setMergeColumn(array(
            'columns' => array('A'),
            'rows' => array(
                array(5, 7),
            )
        ));
        $sheet->setMergeColumn(array(
            'columns' => array('B','C', 'D', 'E', 'F'),
            'rows' => array(
                array(5, 6),
            )
        ));
        $sheet->cell('A5', 'เที่ยว');
        $sheet->cell('B5', 'อุณหภูมิน้ำ');
        $sheet->cell('B7','(เฉลี่ย °C)');
        $sheet->cell('C5', 'ชนาด');
        $sheet->cell('C7', '(ตัว/ก.ก.)');
        $sheet->cell('D5', 'ใหญ่');
        $sheet->cell('D7', 'กรัม');
        $sheet->cell('E5', 'เล็ก');
        $sheet->cell('E7', 'กรัม');
        $sheet->cell('F5', 'U.F.');
        $sheet->cell('F7', 'กรัม');

        //Defect
        $sheet->mergeCells('G5:P5');
        $sheet->cell('G5', 'ข้อบกพร่อง');
        //DF Shrimp Dead
        $sheet->mergeCells('G6:H6');
        $sheet->cell('G6', 'กุ้งตาย');
        $sheet->cell('G7', 'กรัม');
        $sheet->cell('H7', '%');
        //DF Soft Shell
        $sheet->mergeCells('I6:J6');
        $sheet->cell('I6', 'กุ้งนิ่ม');
        $sheet->cell('I7', 'กรัม');
        $sheet->cell('J7', '%');
        //Df Bk Line
        $sheet->mergeCells('K6:L6');
        $sheet->cell('K6', 'แก้มขีดดำ');
        $sheet->cell('K7', 'กรัม');
        $sheet->cell('L7', '%');
        //Df Scar
        $sheet->mergeCells('M6:N6');
        $sheet->cell('M6', 'แก้มขีดดำ');
        $sheet->cell('M7', 'กรัม');
        $sheet->cell('N7', '%');
        //Df Disabled
        $sheet->mergeCells('O6:P6');
        $sheet->cell('O6', 'พิการ');
        $sheet->cell('O7', 'กรัม');
        $sheet->cell('P7', '%');

        //Car Release
        $sheet->mergeCells('Q5:R5');
        $sheet->cell('Q5', 'เวลา');
        $sheet->setMergeColumn(array(
            'columns' => array('Q', 'R'),
            'rows' => array(
                array(6, 7),
            )
        ));
        $sheet->cell('Q6', 'ปล่อยรถ');
        $sheet->cell('R6', 'รอลง');

        //Real Shrimp Dead
        $sheet->mergeCells('S5:T5');
        $sheet->cell('S5', 'กุ้งตาย');
        $sheet->cell('S6', 'นน.');
        $sheet->cell('S7', '(KG)');
        $sheet->setMergeColumn(array(
            'columns' => array('T'),
            'rows' => array(
                array(6, 7),
            )
        ));
        $sheet->cell('T6', '%');

        //Result Weight
        $sheet->mergeCells('U5:V5');
        $sheet->cell('U5', 'น้ำหนักหักตะกร้า');
        $sheet->cell('U6', 'RM1');
        $sheet->cell('U7', '(KG)');
        $sheet->cell('V6' . 'สะสม');
        $sheet->cell('V7', '(KG)');
        $sheet->getStyle('A1:V7')
            ->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
//                $sheet->setAutoSize(true);
        $sheet->setWidth(array(
//                    'A'     =>  5,
            'B' => 20
        ));
        return $sheet;
    }

    //Table Content
    public function setTableContent($sheet, $result)
    {
        $i = 8;
        foreach ($result->shrimpReceiving as $item) {
            $sheet->setHeight($i, 25);

            $sheet->cell('A' . $i, $item->round);
            $sheet->cell('B' . $i, $item->avg_temp);
            $sheet->cell('C' . $i, $item->shrimp_size);
            $sheet->cell('D' . $i, $item->shrimp_big);
            $sheet->cell('E' . $i, $item->shrimp_small);
            $sheet->cell('F' . $i, $item->shrimp_uf);

            //Defect
            //DF Shrimp Dead
            $sheet->cell('G' . $i, $item->df_shrimp_dead);
            $sheet->cell('H' . $i, $item->df_s_dead_p);
            //DF Soft Shell
            $sheet->cell('I' . $i, $item->df_shrimp_soft_shell);
            $sheet->cell('J' . $i, $item->df_s_soft_shell_p);
            //Df Bk Line
            $sheet->cell('K' . $i, $item->df_shrimp_bk_line);
            $sheet->cell('L' . $i, $item->df_s_bk_line_p);
            //Df Scar
            $sheet->cell('M' . $i, $item->df_shrimp_scar);
            $sheet->cell('N' . $i, $item->df_s_scar_p);
            //Df Disabled
            $sheet->cell('O' . $i, $item->df_shrimp_disabled);
            $sheet->cell('P' . $i, $item->df_s_disabled_p);

            //Car Release
            $sheet->cell('Q' . $i, $item->car_release);
            $sheet->cell('R' . $i, $item->car_waiting_time);

            //Real Shrimp Dead
            $sheet->cell('S' . $i, $item->real_shrimp_dead);
            $sheet->cell('T' . $i, $item->r_shrimp_dead_percent);

            //Result Weight
            $sheet->cell('U' . $i, $item->weight);
            $sheet->cell('V' . $i, $item->sumRowWeight);
            $sheet->getStyle('A' . $i . ':V' . $i)
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $i++;
        }
        $rowHeight=collect([]);
//        $row=$i;
//        for($j=$row;$j<=$row+7;$j++){
//            $rowHeight->push(25);
//        }
//        $sheet->setHeight($rowHeight->toArray());
        //Total Weight
        $sheet->mergeCells('A'.($i+1).':D'.($i+1));
        $sheet->cell('A'.($i+1),'สรุปผลการบันทึก');


        $sheet->mergeCells('A'.($i+2).':B'.($i+2));
        $sheet->cell('A'.($i+2),'น้ำหนักรวม');
        $sheet->cell('C'.($i+2),$result->total_shrimp_weight);
        $sheet->cell('D'.($i+2),'กก.');

        //Total Shrimp Dead
        $sheet->mergeCells('A'.($i+3).':B'.($i+3));
        $sheet->cell('A'.($i+3),'');
        $sheet->cell('C'.($i+3),'กก.');
        $sheet->cell('D'.($i+3),'%');
        //Shrimp Dead
        $sheet->mergeCells('A'.($i+4).':B'.($i+4));
        $sheet->cell('A'.($i+4),'กุ้งตายใน');
        $sheet->cell('C'.($i+4),$result->shrimp_dead);
        $sheet->cell('D'.($i+4),$result->shrimp_dead_p);
        //Last Five
        $sheet->mergeCells('A'.($i+5).':B'.($i+5));
        $sheet->cell('A'.($i+5),'กุ้งตาย 5 เที่ยวสุดท้าย');
        $sheet->cell('C'.($i+5),$result->last_five_shrimp_dead);
        $sheet->cell('D'.($i+5),$result->last_five_shrimp_dead_p);
        //Total Dead
        $sheet->mergeCells('A'.($i+6).':B'.($i+6));
        $sheet->cell('A'.($i+6),'รวมกุ้งตาย');
        $sheet->cell('C'.($i+6),$result->total_shrimp_dead);
        $sheet->cell('D'.($i+6),$result->total_shrimp_dead_p);
        //Shrimp Soft
        $sheet->mergeCells('A'.($i+7).':B'.($i+7));
        $sheet->cell('A'.($i+7),'กุ้งนิ่ม');
        $sheet->cell('C'.($i+7),$result->real_shrimp_soft);
        $sheet->cell('D'.($i+7),$result->real_shrimp_soft_p);

        $sheet->getStyle('A' . $i . ':V' . ($i+7))
            ->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $i++;



    }

    //Calculate All Result
    public function calAllResult($id)
    {
        $last_five_shrimp_dead = 0;
        $shrimp_dead = 0;
        $total_shrimp_weight = 0;
        $last_five_status = 0;
        $last_five_records = null;
        $recorder = QcSupplierReceiving::with(['shrimpReceiving' => function ($query) {
            $query->orderBy('round', 'asc');
        }, 'shrimpReceiving.waterTemp', 'supplier'])
            ->where('id', $id)->first();
        $sumWeight = 0;

        foreach ($recorder->shrimpReceiving as $item) {
            $shrimp_dead_percent = 0;
            //Sum Row Weight
            $sumWeight += $item->weight;
            $item->sumRowWeight = $sumWeight;
            //Shrimp Dead Percent
            $shrimp_dead_percent = number_format($item->real_shrimp_dead / $item->weight * 100, 2);
            $item->r_shrimp_dead_percent = $shrimp_dead_percent;
            //Avg Temp
            $item->avg_temp = $item->waterTemp->avg('value');
            //Defect
            $item->df_s_dead_p = $this->dfToPercent($item->df_shrimp_dead);
            $item->df_s_semi_soft_p = $this->dfToPercent($item->df_semi_soft);
            $item->df_s_soft_shell_p = $this->dfToPercent($item->df_shrimp_soft_shell);
            $item->df_s_scar_p = $this->dfToPercent($item->df_shrimp_scar_p);
            $item->df_s_bk_line_p = $this->dfToPercent($item->df_shrimp_bk_line);
            $item->df_s_disabled_p = $this->dfToPercent($item->df_shrimp_disabled);
        }

        $recorder->total_shrimp_weight = $recorder->shrimpReceiving->sum('weight');
        //If Have Last Five Status

        if ($recorder->last_five_round_status == 1) {
            $last_five_records = $recorder->shrimpReceiving->splice(-5);
            //Last Five Shrimp Dead
            $recorder->last_five_shrimp_dead = $last_five_records->sum('real_shrimp_dead');
        } else {
            //Last Five Shrimp Dead Percent
            $recorder->last_five_shrimp_dead_p = $this->shrimpDeadToPercent($recorder->last_five_shrimp_dead, $recorder->total_shrimp_weight);
            $recorder->last_five_shrimp_dead = 0;
        }
        //Shrimp dead
        $recorder->shrimp_dead = $recorder->shrimpReceiving->sum('real_shrimp_dead');
        //Shrimp Dead Percent
        $recorder->shrimp_dead_p = $this->shrimpDeadToPercent($recorder->shrimp_dead, $recorder->total_shrimp_weight);
        //Shrimp Soft Percent
        $recorder->real_shrimp_soft_p=$this->shrimpDeadToPercent($recorder->real_shrimp_soft,$recorder->total_shrimp_weight);
        //Total Shrimp Dead
        $recorder->total_shrimp_dead = $recorder->shrimp_dead + $recorder->last_five_shrimp_dead;
        //Total Shrimp Dead Percent
        $recorder->total_shrimp_dead_p = $recorder->shrimp_dead_p + $recorder->last_five_shrimp_dead_p;
//        dd($recorder);
        return $recorder;
    }

    //Cal Defect Percent
    public function dfToPercent($item)
    {
        return number_format($item / 2000 * 100, 2);
    }

    //Cal Shrimp Dead Percent
    public function shrimpDeadToPercent($item, $total_weight)
    {
        return number_format($item / $total_weight * 100, 2);
    }
}
