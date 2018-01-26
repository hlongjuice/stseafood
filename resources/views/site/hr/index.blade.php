@extends('site.layouts.master')
@section('content')
    {{--General Menu--}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                ฝ่ายทรัพยากรบุคคล
            </div>
        </div>
        <div class="panel-body">
            {{--Car Usage--}}
            <div class="col-xs-12 col-md-4 admin-menu">
                <a href="{{route('hr.car_usage.by_month.index')}}">
                    <div class="icon">
                        {{--<img src="{{asset('images/icons/package2.svg')}}">--}}
                        {{--<i class="fa fa-map-marker" aria-hidden="true"></i>--}}
                    </div>
                    <div class="title">
                        สรุปการใช้รถประจำเดือน
                    </div>
                    <div class="highlight bg-color-blue"></div>
                </a>
            </div>
            {{--Cold Storage--}}
            <div class="col-xs-12 col-md-4 admin-menu">
                <a href="{{route('hr.car_usage.by_year.index')}}">
                    <div class="icon">
                        {{--<img src="{{asset('images/icons/package2.svg')}}">--}}
                        {{--<i class="fa fa-map-marker" aria-hidden="true"></i>--}}
                    </div>
                    <div class="title">
                        สรุปการใช้รถประจำปี
                    </div>
                    <div class="highlight bg-color-blue"></div>
                </a>
            </div>
        </div>
    </div>

@endsection