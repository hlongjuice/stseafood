@extends('site.layouts.master')
@section('content')
    {{--General Menu--}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                ระบบทั่วไป
            </div>
        </div>
        <div class="panel-body">
            {{--Tracking--}}
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

@endsection