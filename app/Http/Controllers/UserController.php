<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function getUserDetails($id){
        $user=User::with('details')->where('id',$id)->first();
        return response()->json($user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.register');
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
        ],[
            'username.required'=>'กรุณากรอก Username',
            'username.unique'=>'username นี้มีการใช้งานแล้ว',
            'password.confirmed'=>'รหัสผ่านไม่ตรงกัน',
            'password.required'=>'กรุณากรอกรหัสผ่าน'
        ]);
        $newUser=User::create([
            'name'=>$request->input('name'),
            'username'=>$request->input('username'),
            'password'=>bcrypt($request->input('password'))
        ]);
        return redirect('/home');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
