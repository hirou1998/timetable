<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Period;
use App\User;
use App\Semester;

class PeriodController extends Controller
{
    public function index(Period $period, User $user, Semester $semester, Request $request)
    {
        if($request->day_of_week){ //特定の曜日の授業だけを取得する場合
            return $period->where('user_id', $user->id)->where('day_of_week', $request->day_of_week)->orderby('period')->get();
        }

        return $period->where('user_id', $user->id)->where('year', $request->year)->where('type', $request->type)->orderby('period')->orderby('day_of_week')->get();
    }
}
