<?php

namespace App\Http\Controllers\Api\Production;

use App\Models\Production\ProductionShrimpType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShrimpTypeController extends Controller
{
    public function getAllType(){
        $types=ProductionShrimpType::all();
        return response()->json($types);
    }
}
