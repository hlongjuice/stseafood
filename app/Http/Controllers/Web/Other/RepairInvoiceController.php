<?php
namespace App\Http\Controllers\Web\Other;

use App\Models\RepairInvoice;
//use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class RepairInvoiceController extends Controller
{
    private $_statusApprove = 2;

    public function index()
    {
        $date = Carbon::now()->toDateString();
        $records = RepairInvoice::whereDate('date', $date)
            ->where('status_id', $this->_statusApprove)
            ->orderBy('time', 'desc')
            ->get();
        return view('site.other.repair_invoice.index')->with('records', $records);
    }

    //get Record By Date
    public function getRecordByDate(Request $request)
    {
        $records = RepairInvoice::whereDate('date', $request->input('date'))
            ->where('status_id', $this->_statusApprove)
            ->orderBy('time', 'desc')
            ->get();
        return view('site.other.repair_invoice.index')->with('records', $records);
    }

    //get Excel
    public function getInvoiceExcel($id)
    {
        $invoice = RepairInvoice::with('approver', 'sender', 'division')->where('id', $id)
            ->first();
        Excel::create('report', function ($excel) use ($invoice) {
            $excel->sheet('sheet_1', function ($sheet) use ($invoice) {
//                $sheet->setAllBorders(\PHPExcel_Style_Border::BORDER_NONE);
                $sheet->setStyle(array(
                    'font' => array(
                        'size' => 15,
//                        'margin-left'=>2
//                        'bold' => true
                    )
                ));
                $sheet->setpaperSize(1);
                //Row Height
                $rowHeight = collect([]);
                for ($i = 1; $i <= 18; $i++) {
                    $rowHeight->push(40);
                }
                $sheet->setHeight($rowHeight->toArray());
                $sheet->setAllborders('thin');
//                $sheet


                $sheet->setOrientation('landscape');
//                $sheet->setPageMargin(array(
//                    0.25, 0.30, 0.25, 0.30
//                ));
                $sheet->setFitToPage(true);
                $sheet->mergeCells('A1:L2');
                $sheet->mergeCells('A3:L3');
                //Surat SeaFood Header
                $sheet->cell('A1', function ($cell) {
                    $cell->setValue('บริษัท สุราษฎร์ซีฟู๊ดส์ จำกัด');
                    $cell->setFont(array(
                        'size' => '18',
                        'bold' => true));
//                    $cell->setAlignment('center');
                });
                $sheet->getStyle('A1')
                    ->getAlignment()
                    ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $sheet->cell('A3', function ($cell) {
                    $cell->setValue('ใบแจ้งซ่อม');
                });
                $sheet->getStyle('A3')
                    ->getAlignment()
                    ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

                //Left Column
                //Date Send
                $sheet->cell('A4', ' วันที่');
                $sheet->cell('B4', ' ' . $invoice->date);
                //Time
                $sheet->cell('C4', ' เวลา');
                $sheet->cell('D4', ' ' . $invoice->time);
                //To Receiver
                $sheet->cell('A5', ' ถึง');
                $sheet->mergeCells('B5:D5');
                $sheet->cell('B5', ' ' . $invoice->receiver->name);
                //Item
                $sheet->mergeCells('A6:D6');
                $sheet->cell('A6', ' เครื่องจักร');
                $sheet->mergeCells('A7:D7');
                $sheet->cell('A7', ' ' . $invoice->item);
                //Item Details
                $sheet->mergeCells('A8:D8');
                $sheet->cell('A8', ' รายละเอียดการชำรุด');
                $sheet->mergeCells('A9:D12');
                $sheet->cell('A9', ' ' . $invoice->item_details);

                $sheet->getStyle('A9')
                    ->getAlignment()
//                    ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
                //Sender
                $sheet->cell('A13', ' ผู้ส่งซ่อม');
                $sheet->mergeCells('B13:D13');
                $sheet->cell('B13', ' ' . $invoice->sender->name . ' ' . $invoice->sender->lastname);
                //Division
                $sheet->cell('A14', ' แผนก');
                $sheet->mergeCells('B14:D14');
                $sheet->cell('B14', ' ' . $invoice->division->name);
                //Approver
                $sheet->cell('A15', ' ผู้อนุมัติ');
                $sheet->mergeCells('B15:D15');
                $sheet->cell('B15', ' ' . $invoice->approver->name . ' ' . $invoice->approver->lastname);
                //Rating
                $sheet->cell('A16', ' ประเมิณการซ่อม');
                $sheet->mergeCells('B16:D16');
                //Repair Order By
                $sheet->cell('A17', ' ผู้สั่งซ่อม');
                $sheet->mergeCells('B17:D17');
                //Order Repair Date
                $sheet->cell('A18', ' วันที่');
                $sheet->mergeCells('B18:D18');
                /***** End Column Left ******/
                /******** Column Center ********/
                //Number Receiver
                $sheet->cell('E4', ' เลขที่รับ');
                $sheet->mergeCells('F4:H4');
                //Date
                $sheet->cell('E5', ' รับวันที่');
                //Time
                $sheet->cell('G5', ' เวลา');
                //Repair Man
                $sheet->cell('E6', ' ผู้ซ่อม');
                //Repair Order
                $sheet->cell('G6', ' ผู้สั่งงาน');
                //Dead Line
                $sheet->cell('E7', ' กำหนดเสร็จ');
                $sheet->mergeCells('F7:H7');
                //Repair List
                $sheet->mergeCells('E8:H8');
                $sheet->cell('E8', ' รายการซ่อม');
                $sheet->mergeCells('E9:H18');
                /********End Column Center*********/

                /*Column Right*/
                //Item in Line Product
                $sheet->mergeCells('I4:L4');
                $sheet->cell('I4', ' เครื่องมืออุปกรณ์ที่นำเข้าไลน์ผลิต');
                $sheet->mergeCells('I5:L11');
                //Clean Unit
                $sheet->mergeCells('J12:L12');
                $sheet->cell('J12', ' ทำความสะอาดหน่วยซ่อม');
                //Repair Finished
                $sheet->cell('I13', ' ซ่อมเสร็จวันที่');
                $sheet->cell('K13', ' เวลา');
                //Repair Man
                $sheet->cell('I14', ' ผู้ซ่อม');
                $sheet->mergeCells('J14:L14');
                //Record Repair
                $sheet->mergeCells('J15:L15');
                $sheet->cell('J15', ' ไม่ต้องลงประวัติ');
                $sheet->mergeCells('J16:L16');
                $sheet->cell('J16', ' ลงประวัติ');
                //Checker Man
                $sheet->cell('I17', ' ผู้ตรวจสอบ');
                $sheet->cell('K17', ' วันที่');

                //Manager Know
                $sheet->cell('I18', ' ผจก.รับทราบ');
                $sheet->mergeCells('J18:L18');

            });
        })->export('xls');
    }

    public function getExcel($id){
        $invoice = RepairInvoice::with('approver', 'sender', 'division')->where('id', $id)
            ->first();
      /*  Excel::create('New file', function($excel) use ($invoice) {

            $excel->sheet('New sheet', function($sheet) use ($invoice) {
                $sheet->setOrientation('landscape');
                $sheet->loadView('site.other.repair_invoice.report')->with('invoice',$invoice);

            });

        })->export('pdf');*/
//        return view('site.other.repair_invoice.report2')->with('invoice',$invoice);
        $data=[
            'invoice'=>$invoice
        ];
        $pdf=Pdf::loadView('site.other.repair_invoice.report',$data);
        $pdf->mpdf->setDisplayMode('real');
//        dd($pdf);
        $pdf->mpdf->title="Yo!!";
        $pdf->mpdf->SetWatermarkText('DRAFT');
        $pdf->mpdf->showWatermarkText = true;
//        dd($pdf->mpdf);
//        $pdf->mpdf->ZoomMode=20;
//        return $pdf->Output('filename.pdf','I');
        return $pdf->stream('document.pdf');
    }
}
