<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
class CustomLogOutController extends Controller
{
    public function logout(Request $request){
        $result= $request->user()->token()->delete();
        return response()->json($result);
    }
}
