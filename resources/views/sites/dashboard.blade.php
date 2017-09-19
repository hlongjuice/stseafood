@extends('site.layouts.master_left_sidebar')
@section('content')
    {{--Customer Menu--}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                ผู้ใช้ทั่วไป
            </div>
        </div>
        <div class="panel-body">
            {{--Tracking--}}
            <div class="col-xs-12 col-md-4 admin-menu">
                <a href="">
                    <div class="icon">
                        <img src="{{asset('images/icons/package2.svg')}}">
                        {{--<i class="fa fa-map-marker" aria-hidden="true"></i>--}}
                    </div>
                    <div class="title">
                        ระบุเส้นทางการจัดส่ง
                    </div>
                    <div class="highlight bg-color-blue"></div>
                </a>
            </div>
            {{--Tracking Histroty--}}
            <div class="col-xs-12 col-md-4 admin-menu">
                <a href="">
                    <div class="icon">
                        <img src="{{asset('images/icons/tracking_history.svg')}}">
                        {{--<i class="fa fa-bookmark"></i>--}}
                    </div>
                    <div class="title">
                        ประวัติการใช้บริการ
                    </div>
                    <div class="highlight bg-color-blue"></div>
                </a>
            </div>

        </div>
    </div>

@endsection