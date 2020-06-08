<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Period;

class PeriodController extends Controller
{
    public function destroy(Period $period){
        $period->delete();
        return response()->json([]);
    }
}
