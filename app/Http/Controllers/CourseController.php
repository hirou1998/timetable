<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;

class CourseController extends Controller
{

    public function update(Course $course, Request $request)
    {
        $course->update([
            'name' => $request->name,
            'teacher' => $request->teacher,
            'type' => $request->type
        ]);
        return $course->updatePeriod($course, $request);
    }

}
