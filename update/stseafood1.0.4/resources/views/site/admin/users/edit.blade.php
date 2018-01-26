@extends('site.layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">แก้ไขข้อมูล Username: {{$user->username}}</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST"
                              action="{{ route('admin.users.update',$user->id) }}">
                            <input value="PUT" type="hidden" name="_method" >
                            {{ csrf_field() }}

                            {{--User Types--}}
                            <div class="form-group">
                                <label for="divisions" class="col-md-4 control-label">ประเภทผู้ใช้งาน</label>
                                <div class="col-md-6">
                                    <select id="divisions" name="type_id" class="form-control" required>
                                        @foreach($types as $type)
                                            <?php $selected='';?>

                                            @if($user->type_id==$type->id)
                                                <?php $selected='selected';?>
                                            @endif
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
                                           value="{{$user->name}}" required autofocus>

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
                                           value="{{ $user->lastname}}" required autofocus>

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
                                        @foreach($divisions as $division)
                                            <?php $selected='';?>

                                            @if($user->division_id==$division->id)
                                                <?php $selected='selected';?>
                                            @endif
                                            <option {{$selected}} value="{{$division->id}}">{{$division->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{--Extra Option--}}
                            <div class="form-group">
                                {{--Car Assign--}}
                                <div class="row">
                                    <?php
                                        $car_approve="";
                                        $car_assign="";
                                        $repair_approve="";
                                    ?>
                                    @if($user->car_assign==1)
                                        <?php $car_assign='checked' ?>
                                        @endif
                                    @if($user->car_approve==1)
                                            <?php $car_approve='checked' ?>
                                        @endif
                                        @if($user->repair_approve==1)
                                            <?php $repair_approve='checked' ?>
                                            @endif
                                    <div class="col-md-offset-4 col-xs-12 col-md-8">
                                        <div class="row">
                                            <div class="col-xs-2">
                                                <div class="checkbox">
                                                    <label>
                                                        <input {{$car_assign}} name="car_assign" type="checkbox" value="1">
                                                        จัดรถ
                                                    </label>
                                                </div>
                                            </div>
                                            {{--Car Approve--}}
                                            <div class="col-xs-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input {{$car_approve}} name="car_approve" type="checkbox" value="1">
                                                        อนุมัติการขอใช้รถ
                                                    </label>
                                                </div>
                                            </div>
                                            {{--Repair Approve--}}
                                            <div class="col-xs-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input {{$repair_approve}} name="repair_approve" type="checkbox" value="1">
                                                        อนุมติการส่งซ่อม
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
                                        แก้ไข
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
