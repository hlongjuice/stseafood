@extends('site.layouts.master')
@section('content')
    {{--Customer Menu--}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                รายงานอุณหภูมิห้องเย็น
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-5">
                    <form class="form-horizontal" role="form" method="POST"
                          action="{{ route('engineer.cold_storage.getRecordByDate') }}">
                        {{ csrf_field() }}
                        <label>เลือกวันที่: <input name="date" value="{{$today}}" type="text" id="datepicker"></label>
                        <button class="btn btn-primary" type="submit">ค้นหา</button>
                    </form>
                </div>
                <div style="margin-top:20px;" class="col-xs-12">
                   {{$today}}
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
                                    <td><a href="{{route('engineer.cold_storage.getExcel',$today)}}"
                                           class="btn btn-primary">ดาวโหลด</a></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
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