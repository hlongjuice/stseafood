@extends('site.layouts.master')
@section('content')
    {{--Customer Menu--}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                สรุปการใช้รถประจำเดือน {{$thai_month}} {{$year}}
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-5">
                    <form class="form-horizontal" role="form" method="POST"
                          action="{{ route('hr.car_usage.by_month.get') }}">
                        {{ csrf_field() }}
                        <label>เลือกวันที่: <input name="date" type="text" id="datepicker"></label>
                        <button class="btn btn-primary" type="submit">ค้นหา</button>
                    </form>
                </div>
                <div style="margin-top:20px;" class="col-xs-12">
                    <label>{{$thai_month}} {{$year}}</label>
                    {{--</div>--}}
                </div>
                <div class="col-xs-12">
                    <div class="table-responsive">
                        <table id="record-table" class="table table-striped table-bordered" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <td>ประเภท</td>
                                <td>หมายเลขรถ</td>
                                <td>ป้ายทะเบียน</td>
                                <td>ดาวโหลด</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cars as $group)
                                @foreach($group as $car)
                                    <tr>
                                        <td>{{$car->carType->name}}</td>
                                        <td>{{$car->car_number}}</td>
                                        <td>{{$car->plate_number}}</td>
                                        <td><a href="{{route('hr.car_usage.by_month.getExcel',[$date,$car->id])}}"
                                               class="btn btn-primary">ดาวโหลด</a></td>
                                    </tr>
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .ui-datepicker-calendar {
            display: none;
        }
    </style>

@endsection

@section('script')
    <script>
        $('#record-table').DataTable({
            "paging": false
        });

        $("#datepicker").datepicker(
                {
                    showButtonPanel: true,
                    dateFormat:'yy-mm',
                    onClose: function(dateText, inst) {
                        var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                        var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                        $(this).datepicker('setDate', new Date(year, month, 1));
                    }
                }
        );
    </script>
@endsection