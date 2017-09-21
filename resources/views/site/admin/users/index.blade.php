@extends('site.layouts.master')
@section('content')
    {{--Customer Menu--}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                เอกสารแจ้งซ่อม
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-3">
                    <a class="btn btn-primary" href="{{route('admin.users.create')}}">เพิ่มสมาชิก</a>
                </div>
                <div class="col-xs-12">
                    <div class="table-responsive">
                        <table id="user-table" class="table table-striped table-bordered " cellspacing="0">
                            <thead>
                            <tr>
                                <td>username</td>
                                <td>ชื่อ-สกุล</td>
                                <td>ฝ่าย</td>
                                <td>แก้ไข</td>
                                <td>เปลี่ยนรหัสผ่าน</td>
                                <td>ลบ</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)

                                <tr>
                                    <td>{{$user->username}}</td>
                                    <td>{{$user->name}} {{$user->lastname}}</td>
                                    @if($user->division)
                                        <td>{{$user->division->name}}</td>
                                    @else
                                        <td> </td>
                                    @endif
                                    <td><a href="{{route('admin.users.edit',$user->id)}}" class="btn btn-primary">แก้ไข</a></td>
                                    <td><a href="{{route('admin.users.edit_password',$user->id)}}" class="btn btn-warning">เปลี่ยนรหัสผ่าน</a></td>
                                    <td>
                                        <form onsubmit="return confirm('ต้องการจะลบรายการ ?')"
                                              action="{{route('admin.users.destroy',$user->id)}}" method="POST">
                                            <input name="_method" type="hidden" value="delete">
                                            {{csrf_field()}}
                                            <button class="btn btn-danger" type="submit">ลบ</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-12">{{$users->links()}}</div>
    </div>

@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('#user-table').DataTable({
                "paging":   false
            });
        });
    </script>
@endsection