<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Event;
use App\User;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index(User $user, Request $request)
    {
        if(!$request->month || !$request->year){
            return response()->json([]);
        }

        $nextMonth = intval($request->month) + 1;

        $requestStart = new Carbon($request->year . '-' . $request->month . '-01');
        $requestEnd = new Carbon($request->year . '-' . $nextMonth . '-00');
        
        $events = $user->events()
            ->whereBetween('start_day', [$requestStart, $requestEnd])
            ->orWhereBetween('end_day', [$requestStart, $requestEnd])
            ->orderby('start_day')->get();
            
        return $events;
    }
}