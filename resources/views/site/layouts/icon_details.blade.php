<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            ลายละเอียดสัญลักษณ์
        </div>
    </div>
    <div class="icon-detail panel-body">
        {{--Sender Icon--}}
        <div class="row">
            <div class="col-xs-4">
                <img src="{{asset('images/map-icon/package2.svg')}}">
            </div>
            <div class="col-xs-7">
               <p>จุดรับสินค้า/จุดเริ่มต้น</p>
            </div>
        </div>
        <div class="line-dot"></div>
        {{--Destination Icon--}}
        <div class="row">
            <div class="col-xs-4">
                <img src="{{asset('images/map-icon/home3.svg')}}">
            </div>
            <div class="col-xs-7">
                <p>จุดหมายปลายทาง</p>
            </div>
        </div>
        <div class="line-dot"></div>
        {{--Deriverly Car Icon--}}
        <div class="row">
            <div class="col-xs-4">
                <img src="{{asset('images/map-icon/delivery-truck.svg')}}">
            </div>
            <div class="col-xs-7">
                <p>รถส่งของ</p>
            </div>
        </div>
        <div class="line-dot"></div>
        @if(!empty($package))
            @if($package->status_id==4)
        {{--Deriverly Car Icon--}}
        <div class="row">
            <div class="col-xs-4">
                <img src="{{asset('images/map-icon/success.svg')}}">
            </div>
            <div class="col-xs-7">
               <p>ส่งของเสร็จเรียบร้อย</p>
            </div>
        </div>
    </div>
    @endif
    @endif
</div>