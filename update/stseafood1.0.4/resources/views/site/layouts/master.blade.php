<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>@yield('title')</title>
    <!-- Bootstrap -->
    <link href="{{ asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.css')}}" rel="stylesheet">
    <link href="{{ asset('css/jquery-ui.min.css')}}" rel="stylesheet">
    <link href="{{ asset('css/jquery-ui.theme.min.css')}}" rel="stylesheet">
    <link href="{{ asset('css/jquery-ui.structure.min.css')}}" rel="styleshhet">
    <link href="{{asset('css/my_tracking.css')}}" rel="stylesheet">
    {{--Yamm is Mega Menu--}}
    <link href="{{ asset('css/yamm-3/yamm.css') }}" rel="stylesheet">
    {{--Map Icon--}}
    <link href="{{asset('css/map-icon/map-icons.css')}}" rel="stylesheet">
    {{--Data Table--}}
    <link href="{{asset('extension/datatable/datatables.min.css')}}" rel="stylesheet">

    @yield('add_css')
</head>
<body class="nav-md">
{{--Nav Bar--}}
<section id="nav-bar">
    @include('site.layouts.nav_bar')
    @yield('nav-bar')
</section>
<div class="main container-fluid">
    {{--Main Content--}}
    <section id="main-content">
        {{--<div class="col-xs-12 col-md-push-3 col-md-9">--}}
        <div class="col-xs-12">
            @yield('content')
        </div>
    </section>

    {{--Side Menu--}}{{--
        <section id="side-menu">
            <div class="col-xs-12 col-md-pull-9 col-md-3">
                --}}{{--@include('site.layouts.side_menu')--}}{{--
                @yield('side_menu_top')
                --}}{{--@include('site.layouts.master_side_menu')--}}{{--
                @yield('side_menu_bottom')
            </div>
        </section>--}}
</div>
<section id="footer">
    @include('site.layouts.footer')
</section>


{{--Script--}}

 <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<script src="{{asset('js/jquery-2.2.4.min.js')}}"></script>
{{--<script src="{{asset('js/jquery.min.js')}}"></script>--}}
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<script src="{{ asset('js/jquery-ui.min.js')}}"></script>

{{-- ******App.js******--}}
{{--Google Map Javascript Api--}}
<script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD5p15SZ4mJm6ZqoIa5STnINkW-OcEBNCw&libraries=geometry,places"></script>
{{--Map Icon--}}
<script src="{{asset('js/map-icon/map-icons.js')}}"></script>
{{--Custom Google map api for this project--}}
<script src="{{ asset('js/gmap.js')}}"></script>
{{--Data Table--}}
<script src="{{asset('extension/datatable/datatables.min.js')}}"></script>
<script>
    $(document).ready(function () {
        $.datepicker.regional['th'] ={
            changeMonth: true,
            changeYear: true,
            showOn: "ปุ่ม",
            buttonImage: 'images/calendar.gif',
            buttonImageOnly: true,
//            dateFormat: 'dd M yy',
            dateFormat:'yy-mm-dd',
            dayNames: ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'],
            dayNamesMin: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
            monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
            monthNamesShort: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
            constrainInput: true,
            yearRange: '-20:+0',
            buttonText: 'เลือก'
        };

        $.datepicker.setDefaults($.datepicker.regional['th']);
    });
</script>
@yield('script')
{{--<script src="{{asset('template/gentelella/build/js/custom.js')}}"></script>--}}
</body>

</html>