<?php

namespace App\Http\Controllers\Api\Production;

use App\Models\Production\ProductionActivity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivityController extends Controller
{
    public function index(){

    }
    public function getAllActivity(){
        $activities=ProductionActivity::all();
//        $helloWorld="Hello World";
//        return response($helloWorld);
        return response()->json($activities);
    }
}
