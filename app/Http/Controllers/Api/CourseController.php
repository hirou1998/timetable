<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Course;
use App\User;

class CourseController extends Controller
{
    public function index(Course $course, Request $request)
    {
        $courseId = $request->course;
        return $course->with('periods')->where('id', $courseId)->get();
    }
}
