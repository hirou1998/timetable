<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Assignment;
use App\Course;

class AssignmentController extends Controller
{
    public function index(Assignment $assignment, Course $course, Request $request)
    {
        $courseId = $request->course;
        return $assignment->where('course_id', $courseId)->orderby('date')->get();
    }
}