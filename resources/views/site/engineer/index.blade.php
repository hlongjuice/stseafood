@extends('site.layouts.master')
@section('content')
    {{--General Menu--}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                ฝ่ายวิศวกรรม
            </div>
        </div>
        <div class="panel-body">
            {{--Water Usage--}}
            <div class="col-xs-12 col-md-4 admin-menu">
                <a href="{{route('engineer.water_usage.index')}}">
                    <div class="icon">
                        {{--<img src="{{asset('images/icons/package2.svg')}}">--}}
                        {{--<i class="fa fa-map-marker" aria-hidden="true"></i>--}}
                    </div>
                    <div class="title">
                        สรุปการใช้น้ำประจำเดือน
                    </div>
                    <div class="highlight bg-color-blue"></div>
                </a>
            </div>
            {{--Cold Storage--}}
            <div class="col-xs-12 col-md-4 admin-menu">
                <a href="{{route('engineer.cold_storage.index')}}">
                    <div class="icon">
                        {{--<img src="{{asset('images/icons/package2.svg')}}">--}}
                        {{--<i class="fa fa-map-marker" aria-hidden="true"></i>--}}
                    </div>
                    <div class="title">
                        รายงานอุณหภูมิห้องเย็น
                    </div>
                    <div class="highlight bg-color-blue"></div>
                </a>
            </div>
        </div>
    </div>

@endsection