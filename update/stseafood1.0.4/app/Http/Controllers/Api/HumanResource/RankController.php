<?php

namespace App\Http\Controllers\Api\HumanResource;

use App\Models\Rank;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RankController extends Controller
{
    public function getAllRank(){
        $ranks=Rank::all();
        return response()->json($ranks);
    }
}
