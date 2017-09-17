<?php

namespace App\Http\Controllers\Api\Eng;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CalculateController extends Controller
{
    public static function getFlow($start, $end)
    {
        if ($start == null || $end == null) {
            return null;
        }
        return $start - $end;
    }

    //Used
    public static function getUsed($start, $end)
    {
        if ($start == null || $end == null) {
            return null;
        }
        return $start - $end;
    }

    //Daily Used
    public static function getDailyUsed($start, $end)
    {
        if ($start == null || $end == null) {
            return null;
        }
        return $start - $end;
    }
    //Monthly Result
    public static function getMonthlyUsed(){
        
    }
}
