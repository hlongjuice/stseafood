<?php

namespace App\Http\Controllers\Web\QC;

use App\Models\QC\QcSupplierReceiving;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Expr\Cast\Object_;

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
        Excel::create('report-' . $result->date, function ($excel) use ($result) {
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
        $rowHeight = collect([]);
        for ($i = 1; $i <= 8; $i++) {
            $rowHeight->push(25);
        }
        $sheet->setHeight($rowHeight->toArray());
        //Sheet Setting
        $sheet->setOrientation('landscape');
        $sheet->setPageMargin(array(
            0.25, 0.30, 0.25, 0.30
        ));
        //Header Table
        $sheet->mergeCells('A1:X1');
        $sheet->cell('A1', function ($cell) {
            $cell->setValue('บริษัท สุราษฎร์ซีฟู้ดส์ จำกัด');

        });
        $sheet->mergeCells('A2:X2');
        $sheet->cell('A2', '21 ถนนเจริญลาภ ตำบลท่าข้าม อำเภอพุนพิน จังหวัดสุราษฎร์ธานี 84130');
        $sheet->mergeCells('A3:X3');
        $sheet->cell('A3', 'รายงานการตรวจรับกุ้งเป็น');
        //Date
        $sheet->cell('A4', 'วันที่');
        $sheet->mergeCells('B4:C4');
        $sheet->cell('B4', $result->date);
        //Supplier
        $sheet->mergeCells('D4:F4');
        $sheet->cell('D4', 'แหล่งที่มาของวัตถุดิบ');
        $sheet->mergeCells('G4:N4');
        $sheet->cell('G4', $result->supplier->name);
        //Pond
        $sheet->cell('O4', 'บ่อ');
        $sheet->cell('P4', $result->pond);
        //Code
        $sheet->mergeCells('Q4:R4');
        $sheet->cell('Q4', 'รหัสวัตถุดิบ');
        $sheet->mergeCells('S4:T4');
        $sheet->cell('S4', $result->code);
        //AVL
        $sheet->mergeCells('U4:V4');
        if($result->avl==1){
            $sheet->cell('U4','✔   AVL');
        }else{
            $sheet->cell('U4','AVL');
        }

        //Waiting List
        $sheet->mergeCells('W4:X4');
        if($result->waiting_list==1){
            $sheet->cell('W4','✔   Waiting List');
        }else{
            $sheet->cell('W4','Waiting List');

        }

        //Details Header
        $sheet->setMergeColumn(array(
            'columns' => array('A'),
            'rows' => array(
                array(5, 7),
            )
        ));
        $sheet->setMergeColumn(array(
            'columns' => array('B', 'C', 'D', 'E', 'F'),
            'rows' => array(
                array(5, 6),
            )
        ));
        $sheet->cell('A5', 'เที่ยว');
        $sheet->cell('B5', 'อุณหภูมิน้ำ');
        $sheet->cell('B7', '(°C)');
        $sheet->cell('C5', 'ชนาด');
        $sheet->cell('C7', '(ตัว/ก.ก.)');
        $sheet->cell('D5', 'ใหญ่');
        $sheet->cell('D7', 'กรัม');
        $sheet->cell('E5', 'เล็ก');
        $sheet->cell('E7', 'กรัม');
        $sheet->cell('F5', 'U.F.');
        $sheet->cell('F7', '');

        //Defect
        $sheet->mergeCells('G5:R5');
        $sheet->cell('G5', 'ข้อบกพร่อง');
        //DF Shrimp Dead
        $sheet->mergeCells('G6:H6');
        $sheet->cell('G6', 'กุ้งตาย');
        $sheet->cell('G7', 'กรัม');
        $sheet->cell('H7', '%');
        //DF Shrimp Semi Soft
        $sheet->mergeCells('I6:J6');
        $sheet->cell('I6', 'นิ่ม');
        $sheet->cell('I7', 'กรัม');
        $sheet->cell('J7', '%');
        //DF Soft Shell
        $sheet->mergeCells('K6:L6');
        $sheet->cell('K6', 'นิ่มวุ้น');
        $sheet->cell('K7', 'กรัม');
        $sheet->cell('L7', '%');
        //Df Scar
        $sheet->mergeCells('M6:N6');
        $sheet->cell('M6', 'แผล');
        $sheet->cell('M7', 'กรัม');
        $sheet->cell('N7', '%');
        //Df Bk Line
        $sheet->mergeCells('O6:P6');
        $sheet->cell('O6', 'แก้มขีดดำ');
        $sheet->cell('O7', 'กรัม');
        $sheet->cell('P7', '%');
        //Df Disabled
        $sheet->mergeCells('Q6:R6');
        $sheet->cell('Q6', 'พิการ');
        $sheet->cell('Q7', 'กรัม');
        $sheet->cell('R7', '%');

        //Car Release
        $sheet->mergeCells('S5:T5');
        $sheet->cell('S5', 'เวลา');
        $sheet->setMergeColumn(array(
            'columns' => array('S', 'T'),
            'rows' => array(
                array(6, 7),
            )
        ));
        $sheet->cell('S6', 'ปล่อยรถ');
        $sheet->cell('T6', 'รอลง');

        //Real Shrimp Dead
        $sheet->mergeCells('U5:V5');
        $sheet->cell('U5', 'กุ้งตาย');
        $sheet->cell('U6', 'นน.');
        $sheet->cell('U7', '(KG)');
        $sheet->setMergeColumn(array(
            'columns' => array('V'),
            'rows' => array(
                array(6, 7),
            )
        ));
        $sheet->cell('V6', '%');

        //Result Weight
        $sheet->mergeCells('W5:X5');
        $sheet->cell('W5', 'น้ำหนักหักตะกร้า');
        $sheet->cell('W6', 'RM1');
        $sheet->cell('W7', '(KG)');
        $sheet->cell('X6', 'สะสม');
        $sheet->cell('X7', '(KG)');
        $sheet->getStyle('A1:X7')
            ->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
//                $sheet->setAutoSize(true);
        $sheet->setWidth(array(
//                    'A'     =>  5,
            'B' => 30,
            'S' =>20,
            'V'=>20
        ));
        return $sheet;
    }

    //Table Content
    public function setTableContent($sheet, $result)
    {
        $i = 8;
//        foreach ($result->shrimpReceiving as $item) {
        foreach($result->allShrimpReceiving as $item){
            $water_temp = "";
            $count = 0;
            $sheet->setHeight($i, 25);

            $sheet->cell('A' . $i, $item->round);
//            $sheet->cell('B' . $i, $item->avg_temp);
            foreach ($item->waterTemp as $temp) {
                if ($count == 0) {
                    $water_temp = $temp->value;
                } else {
                    $water_temp = $water_temp . ', ' . $temp->value;
                }
                $count++;
            }
            $sheet->cell('B' . $i, $water_temp);
            $sheet->cell('C' . $i, number_format($item->shrimp_size, 2));
            $sheet->cell('D' . $i, number_format($item->shrimp_big, 2));
            $sheet->cell('E' . $i, number_format($item->shrimp_small, 2));
            $sheet->cell('F' . $i, number_format($item->shrimp_uf, 2));

            //Defect
            //DF Shrimp Dead
            $sheet->cell('G' . $i, number_format($item->df_shrimp_dead, 2));
            $sheet->cell('H' . $i, $item->df_s_dead_p);
            //DF Shrimp Semi Soft
            $sheet->cell('I' . $i, number_format($item->df_shrimp_semi_soft, 2));
            $sheet->cell('J' . $i, $item->df_s_semi_soft_p);
            //DF Soft Shell
            $sheet->cell('K' . $i, number_format($item->df_shrimp_soft_shell, 2));
            $sheet->cell('L' . $i, $item->df_s_soft_shell_p);
            //Df Scar
            $sheet->cell('M' . $i, number_format($item->df_shrimp_scar, 2));
            $sheet->cell('N' . $i, $item->df_s_scar_p);
            //Df Bk Line
            $sheet->cell('O' . $i, number_format($item->df_shrimp_bk_line, 2));
            $sheet->cell('P' . $i, $item->df_s_bk_line_p);

            //Df Disabled
            $sheet->cell('Q' . $i, number_format($item->df_shrimp_disabled, 2));
            $sheet->cell('R' . $i, $item->df_s_disabled_p);

            //Car Release
            $sheet->cell('S' . $i, $item->car_release_start.'-'.$item->car_release_end);
            $sheet->cell('T' . $i, number_format($item->car_waiting_time,2));

            //Real Shrimp Dead
            $sheet->cell('U' . $i, number_format($item->real_shrimp_dead,2));
            $sheet->cell('V' . $i, number_format($item->r_shrimp_dead_percent,2));

            //Result Weight
            $sheet->cell('W' . $i, number_format($item->weight,2));
            $sheet->cell('X' . $i, number_format($item->sumRowWeight,2));
            $sheet->getStyle('A' . $i . ':X' . $i)
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $i++;
        }
        $rowHeight = collect([]);


        //Total Weight
        $sheet->mergeCells('B' . ($i + 1) . ':B' . ($i + 2));
        $sheet->cell('B' . ($i + 1), function ($cell) {
            $cell->setValue('สรุปผลการบันทึก');
            $cell->setBackground('#D9D9D9');
        });


        $sheet->mergeCells('C' . ($i + 1) . ':D' . ($i + 2));
        $sheet->cell('C' . ($i + 1), 'น้ำหนักรวม');
        $sheet->cell('E' . ($i + 1), 'กก.');
        $sheet->cell('E' . ($i + 2), $result->total_shrimp_weight);
        $sheet->cells('C' . ($i + 1) . ':E' . ($i + 2), function ($cells) {
            $cells->setBackground('#D8E4BC');
        });

        //Shrimp Soft
        $sheet->mergeCells('F' . ($i + 1) . ':F' . ($i + 2));
        $sheet->cell('F' . ($i + 1), 'กุ้งนิ่ม');
        $sheet->cell('G' . ($i + 1), 'กก.');
        $sheet->cell('H' . ($i + 1), '%');
        $sheet->cell('G' . ($i + 2), number_format($result->real_shrimp_soft, 2));
        $sheet->cell('H' . ($i + 2), $result->real_shrimp_soft_p);
        $sheet->cells('F' . ($i + 1) . ':H' . ($i + 2), function ($cells) {
            $cells->setBackground('#FCD5B4');
        });


        //Shrimp Dead
        $sheet->mergeCells('I' . ($i + 1) . ':J' . ($i + 2));
        $sheet->cell('I' . ($i + 1), 'กุ้งตายใน');
        $sheet->cell('K' . ($i + 1), 'กก.');
        $sheet->cell('L' . ($i + 1), '%');
        $sheet->cell('K' . ($i + 2), number_format($result->shrimp_dead, 2));
        $sheet->cell('L' . ($i + 2), $result->shrimp_dead_p);
        $sheet->cells('I' . ($i + 1) . ':L' . ($i + 2), function ($cells) {
            $cells->setBackground('#C5D9F1');
        });


        //Last Five
        $sheet->mergeCells('M' . ($i + 1) . ':O' . ($i + 2));
        $sheet->cell('M' . ($i + 1), 'กุ้งตาย 5 เที่ยวสุดท้าย');
        $sheet->cell('P' . ($i + 1), 'กก.');
        $sheet->cell('Q' . ($i + 1), '%');
        $sheet->cell('P' . ($i + 2), number_format($result->last_five_shrimp_dead, 2));
        $sheet->cell('Q' . ($i + 2), number_format($result->last_five_shrimp_dead_p,2));
        $sheet->cells('M' . ($i + 1) . ':Q' . ($i + 2), function ($cells) {
            $cells->setBackground('#D9D9D9');
        });
        //Total Dead
        $sheet->mergeCells('R' . ($i + 1) . ':S' . ($i + 2));
        $sheet->cell('R' . ($i + 1), 'รวมกุ้งตาย');
        $sheet->cell('T' . ($i + 1), 'กก.');
        $sheet->cell('U' . ($i + 1), '%');
        $sheet->cell('T' . ($i + 2), number_format($result->total_shrimp_dead, 2));
        $sheet->cell('U' . ($i + 2), number_format($result->total_shrimp_dead_p,2));
        $sheet->cells('R' . ($i + 1) . ':U' . ($i + 2), function ($cells) {
            $cells->setBackground('#C4D79B');
        });
        //Small Shrimp B
        $sheet->mergeCells('V' . ($i + 1) . ':V' . ($i + 2));
        $sheet->cell('V' . ($i + 1), 'น้ำหนักกุ้งจิ๋วคืน B');
        $sheet->cell('W' . ($i + 1), 'กก.');
        $sheet->cell('X' . ($i + 1), '%');
        $sheet->cell('W' . ($i + 2), number_format($result->small_shrimp_b, 2));
        $sheet->cell('X' . ($i + 2), $result->small_shrimp_b_p);
        $sheet->cells('V' . ($i + 1) . ':X' . ($i + 2), function ($cells) {
            $cells->setBackground('#BFB1D1');
        });


        //Checker Recorder Approver and Report Number
        $sheet->mergeCells('B' . ($i + 4) . ':D' . ($i + 4));
        $sheet->cell('B'.($i+4),'ผู้บันทึก: '.$result->recorder);
        $sheet->mergeCells('E' . ($i + 4) . ':I' . ($i + 4));
        $sheet->cell('E'.($i+4),'ผู้ตรวจาสอบ: '.$result->checker);
        $sheet->mergeCells('J' . ($i + 4) . ':N' . ($i + 4));
        $sheet->cell('J'.($i+4),'ผู้อนุมัติ: '.$result->approver);
        $sheet->mergeCells('U' . ($i + 4) . ':X' . ($i + 4));
        $sheet->cell('U'.($i+4),$result->report_number);

        $sheet->getStyle('B' . $i . ':X' . ($i + 2))
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
            if($item->weight !=0){
                $shrimp_dead_percent = number_format($item->real_shrimp_dead / $item->weight * 100, 2);
            }
            $item->r_shrimp_dead_percent = $shrimp_dead_percent;
            //Avg Temp
            $item->avg_temp = $item->waterTemp->avg('value');
            //Defect
            $item->df_s_dead_p = $this->dfToPercent($item->df_shrimp_dead);
            $item->df_s_semi_soft_p = $this->dfToPercent($item->df_shrimp_semi_soft);
            $item->df_s_soft_shell_p = $this->dfToPercent($item->df_shrimp_soft_shell);
            $item->df_s_scar_p = $this->dfToPercent($item->df_shrimp_scar);
            $item->df_s_bk_line_p = $this->dfToPercent($item->df_shrimp_bk_line);
            $item->df_s_disabled_p = $this->dfToPercent($item->df_shrimp_disabled);
        }
        //Copy Everything in shrimpReceiving to allShrimpReceiving before splice
        $recorder->allShrimpReceiving = $recorder->shrimpReceiving->all();

        $recorder->total_shrimp_weight = $recorder->shrimpReceiving->sum('weight');
        //If Have Last Five Status

        if ($recorder->last_five_round_status == 1) {
            $last_five_records = $recorder->shrimpReceiving->splice(-5);
            //Last Five Shrimp Dead
            $recorder->last_five_shrimp_dead = $last_five_records->sum('real_shrimp_dead');
            $recorder->last_five_shrimp_dead_p = $this->shrimpDeadToPercent($recorder->last_five_shrimp_dead, $recorder->total_shrimp_weight);
        } else {
            //Last Five Shrimp Dead Percent
            $recorder->last_five_shrimp_dead = 0;
            $recorder->last_five_shrimp_dead_p = $this->shrimpDeadToPercent($recorder->last_five_shrimp_dead, $recorder->total_shrimp_weight);
        }
        //Shrimp dead
        $recorder->shrimp_dead = $recorder->shrimpReceiving->sum('real_shrimp_dead');
        //Shrimp Dead Percent
        $recorder->shrimp_dead_p = $this->shrimpDeadToPercent($recorder->shrimp_dead, $recorder->total_shrimp_weight);
        //Shrimp Soft Percent
        $recorder->real_shrimp_soft_p = $this->shrimpDeadToPercent($recorder->real_shrimp_soft, $recorder->total_shrimp_weight);
        //Total Shrimp Dead
        $recorder->total_shrimp_dead = $recorder->shrimp_dead + $recorder->last_five_shrimp_dead;
        //Total Shrimp Dead Percent
        $recorder->total_shrimp_dead_p = $recorder->shrimp_dead_p + $recorder->last_five_shrimp_dead_p;

        $recorder->small_shrimp_b_p=$this->shrimpDeadToPercent($recorder->small_shrimp_b,$recorder->total_shrimp_weight);
//       dd($recorder);
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
