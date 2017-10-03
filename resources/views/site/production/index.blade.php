@extends('site.layouts.master')
@section('content')
    {{--Customer Menu--}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                สรุปการบันทึกการทำงาน
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-5">
                    <form class="form-horizontal" role="form" method="POST"
                          action="{{ route('production.work.getRecordByDate') }}">
                        {{ csrf_field() }}
                        <label>เลือกวันที่: <input name="date" type="text" id="datepicker"></label>
                        <button class="btn btn-primary" type="submit">ค้นหา</button>
                    </form>
                </div>
                <div style="margin-top:20px;" class="col-xs-12">
                    <label> วันที่ : {{$day}} {{$thai_month}} {{$year}}</label>
                </div>
                <div class="col-xs-12">
                    <div class="table-responsive">
                        <table id="record-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <td>เวลา</td>
                                <td>งาน</td>
                                <td>ชนิดกุ้ง</td>
                                <td>ขนาดกุ้ง</td>
                                <td>กลุ่ม</td>
                                <td>ดาวโหลด</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($result->productionDateTime as $time)
                                @foreach($time->productionWork as $work)
                                    <tr>
                                        <td>{{$time->time_start}}-{{$work->p_time_end}}</td>
                                        <td> {{$work->productionActivity->name}}</td>
                                        <td>{{$work->productionShrimpType->name}}</td>
                                        <td>{{$work->productionShrimpSize->name}}</td>
                                        <td>{{$work->p_group_id}}</td>
                                        <td><a href="{{route('production.work.getExcel',$work->id)}}"
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

@endsection

@section('script')
    <script>
        $('#record-table').DataTable({
            "paging": false
        });

        $("#datepicker").datepicker();
    </script>
@endsection