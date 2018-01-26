@extends('site.layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Register</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST"
                              action="{{ route('admin.users.store') }}">
                            {{ csrf_field() }}

                            {{--User Types--}}
                            <div class="form-group{{ $errors->has('type_id') ? ' has-error' : '' }}">
                                <label for="divisions" class="col-md-4 control-label">ประเภทผู้ใช้งาน</label>

                                <div class="col-md-6">
                                    <select id="user_type" name="type_id" class="form-control" required>
                                        @foreach($types as $type)
                                            <option value="{{$type->id}}">{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{--Name--}}
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">ชื่อ</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name"
                                           value="{{ old('name') }}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            {{--Lastname--}}
                            <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                                <label for="lastname" class="col-md-4 control-label">นามสกุล</label>

                                <div class="col-md-6">
                                    <input id="lastname" type="text" class="form-control" name="lastname"
                                           value="{{ old('lastname') }}" required autofocus>

                                    @if ($errors->has('lastname'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            {{--Divisions--}}
                            <div class="form-group{{ $errors->has('division_id') ? ' has-error' : '' }}">
                                <label for="divisions" class="col-md-4 control-label">ฝ่าย</label>

                                <div class="col-md-6">
                                    <select id="divisions" name="division_id" class="form-control" required>
                                        <option selected="selected" value="">เลือกฝ่าย</option>
                                        @foreach($divisions as $division)
                                            <option value="{{$division->id}}">{{$division->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{--Username--}}
                            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                <label for="username" class="col-md-4 control-label">Username</label>

                                <div class="col-md-6">
                                    <input id="username" type="text" class="form-control" name="username"
                                           value="{{ old('username') }}" required>

                                    @if ($errors->has('username'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            {{--Password--}}
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            {{--Password Confirm--}}
                            <div class="form-group">
                                <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                           name="password_confirmation" required>
                                </div>
                            </div>
                            {{--Extra Option--}}
                            <div class="form-group">
                                {{--Car Assign--}}
                                <div class="row">
                                    <div class="col-md-offset-4 col-xs-12 col-md-8">
                                        <div class="row">
                                            <div class="col-xs-2">
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="car_assign" type="checkbox" value="1">
                                                        จัดรถ
                                                    </label>
                                                </div>
                                            </div>
                                            {{--Car Approve--}}
                                            <div class="col-xs-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="car_approve" type="checkbox" value="1">
                                                        อนุมัติการขอใช้รถ
                                                    </label>
                                                </div>
                                            </div>
                                            {{--Repair Approve--}}
                                            <div class="col-xs-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="repair_approve" type="checkbox" value="1">
                                                        อนุมัติการส่งซ่อม
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        บันทึก
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
