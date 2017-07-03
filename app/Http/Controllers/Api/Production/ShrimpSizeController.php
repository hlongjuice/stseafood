<?php

namespace App\Http\Controllers\Api\Production;

use App\Models\Production\ProductionShrimpSize;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShrimpSizeController extends Controller
{
    public function getAllSize(){
        $size=ProductionShrimpSize::all();
        return response()->json($size);
    }
}
