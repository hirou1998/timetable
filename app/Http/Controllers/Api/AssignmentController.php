<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Assignment;
use App\Course;
use App\User;

class AssignmentController extends Controller
{
    public function sortByCourse(Assignment $assignment, Course $course)
    {
        return $assignment->where('course_id', $course->id)->orderby('date')->get();
    }

    public function sortByUser(Assignment $assignment, User $user, Request $request)
    {
        if($request){ //特定の日時のassignmentsを返す場合
            $date = date('Y-m-d', mktime(0, 0, 0, $request->month, $request->day, $request->year));
            return $assignment->where('date', $date)->get();
        }
        return $assignment->where('user_id', $user->id)->orderby('date')->get();
    }
}