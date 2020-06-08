<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Period;
use App\User;

class PeriodController extends Controller
{
    public function index(Period $period, User $user)
    {
        return $period->where('user_id', $user->id)->orderby('period')->orderby('day_of_week')->get();
    }
}
