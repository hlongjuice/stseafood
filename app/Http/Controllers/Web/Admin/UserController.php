<?php

namespace App\Http\Controllers\Web\Admin;

use App\Models\Division;
use App\Models\UserType;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users=User::with('division')
        ->where('master_admin',null)->orderBy('name','asc')->paginate(50);
//        dd($users);
        return view('site.admin.users.index')->with('users',$users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        $divisions=Division::all();
        $divisions=Division::whereIn('id',[1,6,7,9,11])->get();
        $types=UserType::all();
        return view('site.admin.users.create')->with([
            'divisions'=>$divisions,
            'types'=>$types
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'username'=>'required|unique:users',
            'password'=>'required|min:3|confirmed',
            'password_confirmation'=>'required|min:3',
            'division_id'=>'required',
            'type_id'=>'required'
        ],[
            'username.required'=>'กรุณากรอก Username',
            'username.unique'=>'username นี้มีการใช้งานแล้ว',
            'password.confirmed'=>'รหัสผ่านไม่ตรงกัน',
            'password.required'=>'กรุณากรอกรหัสผ่าน'
        ]);
        User::create([
            'name'=>$request->input('name'),
            'lastname'=>$request->input('lastname'),
            'username'=>$request->input('username'),
            'password'=>bcrypt($request->input('password')),
            'division_id'=>$request->input('division_id'),
            'car_approve'=>$request->input('car_approve'),
            'car_assign'=>$request->input('car_assign'),
            'repair_approve'=>$request->input('repair_approve'),
            'type_id'=>$request->input('type_id')
        ]);
        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user=User::where('id',$id)->first();
        $types=UserType::all();
//        $divisions=Division::all();
        $divisions=Division::whereIn('id',[1,6,7,9,11])->get();
        return view('site.admin.users.edit')->with([
            'user'=>$user,
            'divisions'=>$divisions,
            'types'=>$types
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        User::where('id',$id)->update([
            'name'=>$request->input('name'),
            'lastname'=>$request->input('lastname'),
            'division_id'=>$request->input('division_id'),
            'car_approve'=>$request->input('car_approve'),
            'car_assign'=>$request->input('car_assign'),
            'repair_approve'=>$request->input('repair_approve'),
            'type_id'=>$request->input('type_id')
        ]);
        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//        echo($id);
        User::where('id',$id)->delete();
        return redirect()->route('admin.users.index');
    }

    //Edit Password
    public function editPassword($id){
        $user=User::where('id',$id)->first();
        return view('site.admin.users.edit_password')->with('user',$user);
    }
    //Update Password
    public function updatePassword(Request $request,$id){
        $this->validate($request,[
            'password'=>'required|min:3|confirmed',
            'password_confirmation'=>'required|min:3',
        ],[
            'password.confirmed'=>'รหัสผ่านไม่ตรงกัน',
            'password.required'=>'กรุณากรอกรหัสผ่าน'
        ]);
        User::where('id',$id)->update(
            [
                'password'=>bcrypt($request->input('password'))
            ]);
        return redirect()->route('admin.users.index');
    }
}
