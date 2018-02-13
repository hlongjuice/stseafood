<?php

namespace App\Http\Controllers\Api\Eng;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CalculateController extends Controller
{
    public static function getFlow($current, $previous)
    {
        if ($current == null || $previous == null) {
            return null;
        }
        return $current - $previous;
    }

    //Used
    public static function getUsed($current, $previous)
    {
        if ($current == null || $previous == null) {
            return null;
        }
        return $current - $previous;
    }

    //Daily Used
    public static function getDailyUsed($current, $previous)
    {
        if ($current == null || $previous == null) {
            return null;
        }
        return $current - $previous;
    }
    //Monthly Result
    public static function getMonthlyUsed(){
        
    }
}
