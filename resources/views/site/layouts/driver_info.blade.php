<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            ลายละเอียดคนส่งของ
        </div>
    </div>
    <div class="driver-details-body panel-body">

        <div class="profile" align="center">
            @if($driver->image!=null)
                <img src="{{asset($driver->image)}}">
            @else
                <img src="{{asset('images/members/member_profile.png')}}">
                @endif
        </div>
        {{--Name--}}
        <div class="row">
            <div class="col-xs-5">
                <h5>
                    <i class="fa fa-user-circle-o" aria-hidden="true"></i> ชื่อ/สกุล
                </h5>

            </div>
            <div class="col-xs-7 driver-details-result">: {{$driver->name}} {{$driver->surname}}</div>
        </div>
        {{--Car--}}
        <div class="row">
            <div class="col-xs-5">
                <h5>
                    <i class="fa fa-truck" aria-hidden="true"></i>
                    รถที่ใช้
                </h5>

            </div>
            @if($driver->carDetails)
                <div class="col-xs-7 driver-details-result">: {{$driver->carDetails->car}} {{$driver->carDetails->model}}</div>
            @endif
        </div>
        {{--Car Colr--}}
        <div class="row">
            <div class="col-xs-5">
                <h5>
                    <i class="fa fa-adjust" aria-hidden="true"></i> สี
                </h5>
            </div>
            @if($driver->carDetails)
                <div class="col-xs-7 driver-details-result">: {{$driver->carDetails->color}}</div>
            @endif
        </div>
        {{--Car Plate Number--}}
        <div class="row">
            <div class="col-xs-5">
                <h5>
                    <i class="fa fa-tag" aria-hidden="true"></i> ป้ายทะเบียน
                </h5>
            </div>
            @if($driver->carDetails)
                <div class="col-xs-7 driver-details-result">: {{$driver->carDetails->plate}}</div>
            @endif
        </div>
    </div>
</div>