{{--<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"--}}
{{--xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">--}}
<html>
<head>
    {{--<meta http-equiv="Content-Type" content="text/html; charset=windows-874">--}}
    {{--<meta name="ProgId" content="Excel.Sheet">--}}
    {{--<meta name="Generator" content="Microsoft Excel 14">--}}
    {{--<link id="Main-File" rel="Main-File" href="../invoice_report.htm">--}}
    <!-- <link rel=File-List href=filelist.xml> -->
    {{--<link rel="Stylesheet" href="stylesheet.css">--}}
        {{--<link rel="stylesheet" href="{{asset('css/pdf/pdf/css')}}">--}}
    <style>
        <!--
        table {
            mso-displayed-decimal-separator: "\.";
            mso-displayed-thousand-separator: "\,";
        }

        @page {
            margin: .75in .7in .75in .7in;
            mso-header-margin: .3in;
            mso-footer-margin: .3in;
            mso-page-orientation: landscape;
        }
        .test-thai{
            font-family: 'thsarabunnew' , sans-serif;
        }

        -->
    </style>
    @extends('site.other.repair_invoice.report_style3')
</head>

<body link="blue" vlink="purple">

{{--<a href="javascript:if(window.print)window.print()">Print</a>--}}
<table border="0" cellpadding="0" cellspacing="0" width="1116" style="border-collapse:
 collapse;table-layout:fixed;width:840pt">
    <colgroup>
        <col width="93" span="12" style="mso-width-source:userset;mso-width-alt:2976;
 width:70pt">
        <col width="72" style="mso-width-source:userset;mso-width-alt:2304;width:54pt">
    </colgroup>
    <tbody>
    <tr height="19" style="height:14.25pt">
        <td colspan="12" rowspan="2" height="38" class="xl79" width="1116" style="padding-top:10px; padding-bottom: 10px; font-size:25px; font-weight: bold; height:28.5pt;
  width:840pt">บริษัท สุราษฎร์ซีฟู๊ดส์ จำกัด
        </td>
    </tr>
    <tr height="19" style="height:14.25pt">
    </tr>
    <tr height="19" style="height:14.25pt">
        <td colspan="12" rowspan="2" height="38" class="xl80" style="padding-top:10px; padding-bottom: 10px; font-size: 22px; font-weight: bold; height:28.5pt">ใบแจ้งซ่อม</td>
    </tr>
    <tr height="19" style="height:14.25pt">
    </tr>

    <!-- Left Column -->
    <tr height="40" style="mso-height-source:userset;height:30.0pt">
        <td colspan=2 height="40" class="xl65" style="height:30.0pt;border-top:none">วันที่ : {{$invoice->date}}</td>
        <td colspan=2 class="xl66" style="border-top:none">เวลาส่ง : {{$invoice->time}}</td>
        <td colspan="3" class="xl65" style="border-left:none">เลขรับที่
            ................................
        </td>
        <td class="xl67" style="border-top:none">&nbsp;</td>
        <td colspan="4" class="xl81" style="border-left:none">เครื่องมือ/อุปกรณ์
            ที่นำเข้าไลน์ผลิต (นำออก)
        </td>
    </tr>
    <tr height="40" style="mso-height-source:userset;height:30.0pt">
        <td colspan="4" height="40" class="xl82" style="height:30.0pt">ถึง : {{$invoice->receiver->name}}</td>
        <td colspan="2" class="xl68" style="border-left:none">รับวันที่
            .................................
        </td>
        <td colspan="2" class="xl69">เวลารับ ..................................</td>
        <td colspan="4" class="xl82" style="border-left:none">1)
            ...........................................................
        </td>
    </tr>
    <tr height="40" style="mso-height-source:userset;height:30.0pt">
        <td height="40" class="xl68" colspan="3" style="height:30.0pt;mso-ignore:colspan">เครื่องจักร/อุปกรณ์ที่เสีย(1
            รายการซ่อม/1 ใบ)
        </td>
        <td class="xl69">&nbsp;</td>
        <td colspan="2" class="xl68" style="border-left:none">ผู้ซ่อม
            ...................................
        </td>
        <td colspan="2" class="xl69">ผู้สั่งงาน .................................</td>
        <td colspan="4" class="xl82" style="border-left:none">2)
            ...........................................................
        </td>
    </tr>
    <tr height="40" style="mso-height-source:userset;height:30.0pt">
        {{--<td colspan="4" height="40" class="xl83" style="height:30.0pt">{{$invoice->item}}</td>--}}
        <td colspan="4" height="40" class="xl83" style="height:30.0pt;padding-left:10px;">{{$invoice->item}}</td>
        <td colspan="2" class="xl71" style="border-left:none">กำหนดเสร็จ
            ........................
        </td>
        <td class="xl72">&nbsp;</td>
        <td class="xl73">&nbsp;</td>
        <td colspan="4" class="xl82" style="border-left:none">3)
            ...........................................................
        </td>
    </tr>
    <tr height="40" style="mso-height-source:userset;height:30.0pt">
        <td colspan="4" height="40" class="xl82" style="height:30.0pt">รายละเอียดการชำรุด</td>
        <td colspan="4" class="xl81" style="border-left:none">รายการซ่อม
            <span> ....................................................</span>
        </td>
        <td colspan="4" class="xl82" style="border-left:none">4)
            ...........................................................
        </td>
    </tr>
    <tr height="40" style="mso-height-source:userset;height:30.0pt">
        <td colspan="4" height="40" class="xl83"
            style="height:30.0pt;padding-left:10px;">{{$invoice->item_details}}</td>
        <td colspan="4" class="xl84" style="border-left:none">
            ................................................................
        </td>
        <td colspan="4" class="xl82" style="border-left:none">5)
            ...........................................................
        </td>
    </tr>
    <tr height="40" style="mso-height-source:userset;height:30.0pt">
        <td colspan="4" height="40" class="xl83" style="height:30.0pt">&nbsp;</td>
        <td colspan="4" class="xl84" style="border-left:none">
            ................................................................
        </td>
        <td colspan="4" class="xl82" style="border-left:none">6)
            ...........................................................
        </td>
    </tr>
    <tr height="40" style="mso-height-source:userset;height:30.0pt">
        <td height="40" class="xl68" style="height:30.0pt">ผู้สั่งซ่อม
            : {{' ' . $invoice->sender->name . ' ' . $invoice->sender->lastname}}</td>
        <td class="xl70"></td>
        <td class="xl70"></td>
        <td class="xl69">&nbsp;</td>
        <td colspan="4" class="xl84" style="border-left:none">
            ................................................................
        </td>
        <td class="xl68" style="border-left:none">&nbsp;</td>
        <td class="xl70"></td>
        <td colspan="2" class="xl69">มีต่อด้านหลัง<span style="mso-spacerun:yes">&nbsp;
  </span>[<span style="mso-spacerun:yes">&nbsp;&nbsp;&nbsp;&nbsp; </span>]
        </td>
    </tr>
    <tr height="40" style="mso-height-source:userset;height:30.0pt">
        <td height="40" class="xl68" style="height:30.0pt">แผนก: {{$invoice->division->name}}</td>
        <td class="xl70"></td>
        <td class="xl70"></td>
        <td class="xl69">&nbsp;</td>
        <td colspan="4" class="xl84" style="border-left:none">
            ................................................................
        </td>
        <td colspan="4" class="xl82" style="border-left:none">[<span style="mso-spacerun:yes">&nbsp;&nbsp; </span>]<span
                    style="mso-spacerun:yes">&nbsp; </span>ทำความสะอาดหลังการซ่อม
        </td>
    </tr>
    <tr height="40" style="mso-height-source:userset;height:30.0pt">
        <td height="40" class="xl71" style="height:30.0pt">
            ผู้อนุมัติ: {{' ' . $invoice->approver->name . ' ' . $invoice->approver->lastname}}</td>
        <td class="xl72">&nbsp;</td>
        <td class="xl72">&nbsp;</td>
        <td class="xl73">&nbsp;</td>
        <td colspan="4" class="xl84" style="border-left:none">
            ................................................................
        </td>
        <td colspan="2" class="xl68" style="border-left:none">ซ่อมเสร็จวันที่
            .........................
        </td>
        <td colspan="2" class="xl69">เวลา ....................................</td>
    </tr>
    <tr height="40" style="mso-height-source:userset;height:30.0pt">
        <td colspan="4" height="40" class="xl81" style="height:30.0pt">ประเมิณการซ่อม</td>
        <td colspan="4" class="xl84" style="border-left:none">
            ................................................................
        </td>
        <td colspan="2" class="xl71" style="border-left:none">ผู้ซ่อม
            .......................................
        </td>
        <td class="xl72">&nbsp;</td>
        <td class="xl74">&nbsp;</td>
    </tr>
    <tr height="40" style="mso-height-source:userset;height:30.0pt">
        <td height="40" class="xl87" style="height:30.0pt">[<span style="mso-spacerun:yes">&nbsp;&nbsp; </span>]<span
                    style="mso-spacerun:yes">&nbsp; </span>ดีมาก
        </td>
        <td class="xl86">[<span style="mso-spacerun:yes">&nbsp;&nbsp; </span>]<span
                    style="mso-spacerun:yes">&nbsp; </span>ดี
        </td>
        <td class="xl70">[<span style="mso-spacerun:yes">&nbsp;&nbsp; </span>]<span
                    style="mso-spacerun:yes">&nbsp; </span>พอใช้
        </td>
        <td class="xl69">&nbsp;</td>
        <td colspan="4" class="xl84" style="border-left:none">
            ................................................................
        </td>
        <td colspan="2" class="xl65" style="border-left:none">[<span style="mso-spacerun:yes">&nbsp;&nbsp; </span>]<span
                    style="mso-spacerun:yes">&nbsp; </span>ไม่ต้องลงประวัติ
        </td>
        <td class="xl70"></td>
        <td class="xl75">&nbsp;</td>
    </tr>
    <tr height="40" style="mso-height-source:userset;height:30.0pt">
        <td colspan="4" height="40" class="xl87" style="border-right:.5pt solid black;
  height:30.0pt">[<span style="mso-spacerun:yes">&nbsp;&nbsp; </span>]<span style="mso-spacerun:yes">&nbsp; </span>ปรับปรุง
            <span>.......................................................................</span>
        </td>
        <td colspan="4" class="xl84" style="border-left:none">
            ................................................................
        </td>
        <td colspan="2" class="xl68" style="border-left:none">[<span style="mso-spacerun:yes">&nbsp;&nbsp; </span>]<span
                    style="mso-spacerun:yes">&nbsp; </span>ลงประวัติ
        </td>
        <td class="xl70"></td>
        <td class="xl75">&nbsp;</td>
    </tr>
    <tr height="40" style="mso-height-source:userset;height:30.0pt">
        <td colspan="4" height="40" class="xl68" style="border-right:.5pt solid black;
  height:30.0pt">
            .....................................................................
        </td>
        <td colspan="4" class="xl84" style="border-left:none">
            ................................................................
        </td>
        <td colspan="2" class="xl68" style="border-left:none">ผู้ตรวจสอบ
            ...........................
        </td>
        <td colspan="2" class="xl69">วันที่ ................................</td>
    </tr>
    <tr height="40" style="mso-height-source:userset;height:30.0pt">
        <td colspan="2" height="40" class="xl68" style="height:30.0pt">ผู้สั่งซ่อม
            ..................................
        </td>
        <td colspan="2" class="xl69">วันที่ .......................................</td>
        <td colspan="4" class="xl84" style="border-left:none">
            ................................................................
        </td>
        <td class="xl68" style="border-left:none">ผจก.รับทราบ</td>
        <td colspan="2" class="xl85">.................................</td>
        <td class="xl75">&nbsp;</td>
    </tr>
    <tr height="40" style="mso-height-source:userset;height:30.0pt">
        <td height="40" class="xl76" style="height:30.0pt">&nbsp;</td>
        <td class="xl77">&nbsp;</td>
        <td class="xl77">&nbsp;</td>
        <td class="xl78">&nbsp;</td>
        <td class="xl76" style="border-left:none">&nbsp;</td>
        <td class="xl77">&nbsp;</td>
        <td class="xl77">&nbsp;</td>
        <td class="xl78">&nbsp;</td>
        <td class="xl76" style="border-left:none">&nbsp;</td>
        <td class="xl77">&nbsp;</td>
        <td class="xl77">&nbsp;</td>
        <td class="xl74">&nbsp;</td>
    </tr>
    <!--[if supportMisalignedColumns]-->
    <tr height="0" style="display:none">
        <td width="93" style="width:70pt"></td>
        <td width="93" style="width:70pt"></td>
        <td width="93" style="width:70pt"></td>
        <td width="93" style="width:70pt"></td>
        <td width="93" style="width:70pt"></td>
        <td width="93" style="width:70pt"></td>
        <td width="93" style="width:70pt"></td>
        <td width="93" style="width:70pt"></td>
        <td width="93" style="width:70pt"></td>
        <td width="93" style="width:70pt"></td>
        <td width="93" style="width:70pt"></td>
        <td width="93" style="width:70pt"></td>
    </tr>
    <tr>
        <td width="93" style="width:70pt"></td>
        <td width="93" style="width:70pt"></td>
        <td width="93" style="width:70pt"></td>
        <td width="93" style="width:70pt"></td>
        <td width="93" style="width:70pt"></td>
        <td width="93" style="width:70pt"></td>
        <td width="93" style="width:70pt"></td>
        <td width="93" style="width:70pt"></td>
        <td width="93" style="width:70pt"></td>
        <td width="93" style="width:70pt"></td>
        <td width="93" style="width:70pt"></td>
        <td width="93" style="width:70pt;padding-top:5px;">{{$number}}</td>
    </tr>
    <!--[endif]-->
    </tbody>
</table>
</body>
</html>