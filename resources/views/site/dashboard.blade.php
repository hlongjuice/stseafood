@extends('site.layouts.master')
@section('content')
    {{--Admin Menu--}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                Admin Menu
            </div>
        </div>
        <div class="panel-body">
            {{--Tracking--}}
            <div class="col-xs-12 col-md-4 admin-menu">
                <a href="{{route('admin.users.index')}}">
                    <div class="icon">
                        {{--<img src="{{asset('images/icons/package2.svg')}}">--}}
                        {{--<i class="fa fa-map-marker" aria-hidden="true"></i>--}}
                    </div>
                    <div class="title">
                        ระบบจัดการสมาชิก
                    </div>
                    <div class="highlight bg-color-blue"></div>
                </a>
            </div>
        </div>
    </div>
    {{--General Menu--}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                ระบบทั่วไป
            </div>
        </div>
        <div class="panel-body">
            {{--Repair--}}
            <div class="col-xs-12 col-md-4 admin-menu">
                <a href="{{route('other.repair_invoice.index')}}">
                    <div class="icon">
                        {{--<img src="{{asset('images/icons/package2.svg')}}">--}}
                        {{--<i class="fa fa-map-marker" aria-hidden="true"></i>--}}
                    </div>
                    <div class="title">
                        ระบบแจ้งซ่อม
                    </div>
                    <div class="highlight bg-color-blue"></div>
                </a>
            </div>
        </div>
    </div>
    {{--General Menu--}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                ฝ่ายงานต่างๆ
            </div>
        </div>
        <div class="panel-body">
            {{--QC--}}
            <div class="col-xs-12 col-md-4 admin-menu">
                <a href="{{route('qc.export.index')}}">
                    <div class="icon">
                        {{--<img src="{{asset('images/icons/package2.svg')}}">--}}
                        {{--<i class="fa fa-map-marker" aria-hidden="true"></i>--}}
                    </div>
                    <div class="title">
                        ฝ่ายประกันคุณภาพ
                    </div>
                    <div class="highlight bg-color-blue"></div>
                </a>
            </div>
            {{--Engineer--}}
            <div class="col-xs-12 col-md-4 admin-menu">
                <a href="{{route('engineer.index')}}">
                    <div class="icon">
                        {{--<img src="{{asset('images/icons/package2.svg')}}">--}}
                        {{--<i class="fa fa-map-marker" aria-hidden="true"></i>--}}
                    </div>
                    <div class="title">
                        ฝ่ายวิศวกรรม
                    </div>
                    <div class="highlight bg-color-blue"></div>
                </a>
            </div>
            {{--HR--}}
            <div class="col-xs-12 col-md-4 admin-menu">
                <a href="{{route('hr.car_usage.index')}}">
                    <div class="icon">
                        {{--<img src="{{asset('images/icons/package2.svg')}}">--}}
                        {{--<i class="fa fa-map-marker" aria-hidden="true"></i>--}}
                    </div>
                    <div class="title">
                        ฝ่ายทรัพยากรบุคคล
                    </div>
                    <div class="highlight bg-color-blue"></div>
                </a>
            </div>
            {{--Production--}}
            <div class="col-xs-12 col-md-4 admin-menu">
                <a href="{{route('production.work.index')}}">
                    <div class="icon">
                        {{--<img src="{{asset('images/icons/package2.svg')}}">--}}
                        {{--<i class="fa fa-map-marker" aria-hidden="true"></i>--}}
                    </div>
                    <div class="title">
                        ฝ่ายผลิต
                    </div>
                    <div class="highlight bg-color-blue"></div>
                </a>
            </div>
        </div>
    </div>

@endsection