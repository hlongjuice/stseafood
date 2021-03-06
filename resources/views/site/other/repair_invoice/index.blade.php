@extends('site.layouts.master')
@section('content')
    {{--Customer Menu--}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                เอกสารแจ้งซ่อม
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-5">
                    <form class="form-horizontal" role="form" method="POST"
                          action="{{ route('other.repair_invoice.getRecordByDate') }}">
                        {{ csrf_field() }}
                        <label>เลือกวันที่: <input name="date" type="text" id="datepicker"></label>
                        <button class="btn btn-primary" type="submit">ค้นหา</button>
                    </form>
                </div>
                {{--<div class="row">--}}
                @if($records->count()>0)
                    <div style="margin-top:20px;" class="col-xs-12">
                    <label> วันที่ : {{$records[0]->date}}</label>
                        {{--</div>--}}
                    </div>
                    @else
                    <div class="col-xs-12">
                       <p style="margin-top:20px;"  class="text-danger"> ไม่พบข้อมูล</p>
                    </div>
                @endif
                <div class="col-xs-12">
                    <div class="table-responsive">
                        <table id="record-table" class="table table-striped table-bordered" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <td>เวลา</td>
                                <td>เครื่องจักร</td>
                                <td>ผู้ส่ง</td>
                                <td>ดาวโหลด **หมายเลขใบแจ้งซ่อมสามารถแก้ไขได้</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($records as $record)
                                <tr>
                                    <td>{{$record->time}}</td>
                                    <td>{{$record->item}}</td>
                                    <td>{{$record->sender->name}} {{$record->sender->lastname}}</td>
                                    {{--<td> ระบุหมายเลขใบแจ้งซ่อม : <input type="text" id="invoice_number"> <a id="btn-download"--}}
                                           {{--class="btn btn-primary not-active">ดาวโหลด</a></td>--}}
                                    <td>
                                        <form method="POST" action="{{route('other.repair_invoice.getInvoiceExcel',$record->id)}}">
                                            {{ csrf_field() }}
                                            <input type="text" id="invoice_number" name="invoice_number" value="F-EN-001 Rev.3A">
                                            <button class="btn btn-primary" type="submit">ดาวโหลด</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .not-active {
           /*display: none;*/
        }
    </style>

@endsection

@section('script')
    <script>
        $('#record-table').DataTable({
            "paging": false
        });

        $("#datepicker").datepicker();
    </script>
@endsection