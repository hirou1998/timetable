<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Semester;
use App\User;
use App\Setting;

class SemesterController extends Controller
{
    public function index(Semester $semester, User $user){
        $semesterEnum = $semester->semesterEnum();
        $setting = Setting::where('user_id', $user->id)->get()->first();
        $currentSemester = $semester->currentSemester($setting, $user->id);
        $semesters = $semester->where('user_id', $user->id)->get();

        return [
            'current' => $currentSemester,
            'semesterEnum' => $semesterEnum,
            'semesters' => $semesters
        ];
    }

    public function currentSemester(Semester $semester, User $user){

        
        
        return $currentSemester;
    }
    
    
}