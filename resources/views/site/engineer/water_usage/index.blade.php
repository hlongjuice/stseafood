@extends('site.layouts.master')
@section('content')
    {{--Customer Menu--}}
    <div  class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                สรุปผลการใช้น้ำ
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-5">
                    <form class="form-horizontal" role="form" method="POST"
                          action="{{ route('engineer.water_usage.getRecordByMonth') }}">
                        {{ csrf_field() }}
                        <label>เลือกวันที่: <input name="date" value="{{$year}}-{{$month}}" type="text" id="datepicker"></label>
                        <button class="btn btn-primary" type="submit">ค้นหา</button>
                    </form>
                </div>
                    <div style="margin-top:20px;" class="col-xs-12">
                        @if($month)
                            {{$thai_month}} {{$year}}
                            <div class="col-xs-12">
                                <div class="table-responsive">
                                    <table id="record-table" class="table table-striped table-bordered" cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <td>ดาวโหลด</td>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <tr>
                                            <td><a href="{{route('engineer.water_usage.getExcel',[$month,$year])}}"
                                                   class="btn btn-primary">ดาวโหลด</a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @else
                            <p style="margin-top:20px;"  class="text-danger"> ไม่พบข้อมูล</p>
                            @endif
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