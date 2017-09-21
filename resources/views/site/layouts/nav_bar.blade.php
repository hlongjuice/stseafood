<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{route('dashboard')}}">หน้าหลัก</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            {{--List of Menu--}}
            {{--            <ul class="nav navbar-nav">
                            <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
                            <li><a href="#">ฝ่ายวิศวะกรรม</a></li>
                        </ul>--}}

            {{--Right--}}
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                        ออกจากระบบ
                    </a></li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
                {{--<li class="dropdown">--}}
                {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>--}}
                {{--<ul class="dropdown-menu">--}}
                {{--<li><a href="#">Action</a></li>--}}
                {{--<li><a href="#">Another action</a></li>--}}
                {{--<li><a href="#">Something else here</a></li>--}}
                {{--<li role="separator" class="divider"></li>--}}
                {{--<li><a href="#">Separated link</a></li>--}}
                {{--</ul>--}}
                {{--</li>--}}
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
{{--
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="row">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="col-xs-6 navbar-header">
                <a class="navbar-brand" href="{{route('home')}}">Home</a>
            </div>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
   --}}
{{--     <div class="hidden-md hidden-lg">
            <div class="collapse navbar-collapse" id="user-menu">
                @if(Auth::user())
                <ul class="nav navbar-nav">
                    --}}{{--
--}}
{{--<li><a href="{{route('member.edit',Auth::user()->id)}}" class="">แก้ไขข้อมูลส่วนตัว</a></li>--}}{{--
--}}
{{--
                    <li><a class="btn btn-danger btn-block" href="{{url('/logout')}}">ออกจากระบบ</a></li>
                </ul>
                    @endif
            </div><!-- /.navbar-collapse -->
        </div>--}}{{--

    </div><!-- /.container-fluid -->
</nav>--}}
