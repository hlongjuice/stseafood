<?php

namespace App\Http\Controllers\Api\Production\ExpCalculator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExpirationController extends Controller
{
    public function uploadImage(Request $request){
        return response()->json($request->input());
    }
}
