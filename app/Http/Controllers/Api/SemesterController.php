<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Semester;
use App\User;
use App\Setting;
use App\Type;

class SemesterController extends Controller
{
    public function index(Semester $semester, User $user, Type $type){
        $semesterTypes = $type->index();
        $semesterEnum = $semester->semesterEnum();
        $setting = Setting::where('user_id', $user->id)->get()->first();
        $currentSemester = $semester->currentSemester($setting, $user->id);
        $semesters = $semester->where('user_id', $user->id)->orderby('year')->orderby('type')->get();

        return [
            'current' => $currentSemester,
            'semesterType' => [
                $semesterTypes->type1,
                $semesterTypes->type2,
                $semesterTypes->type3,
                $semesterTypes->type4
            ],
            'semesters' => $semesters
        ];
    }  
}