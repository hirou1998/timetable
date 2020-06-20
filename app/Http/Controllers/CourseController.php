<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;
use App\User;

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

    public function store(User $user, Request $request)
    {
        $new_course = $user->courses()->create([
            'name' => $request->name,
            'teacher' => $request->teacher,
            'type' => $request->type,
        ]);

        $new_course->updatePeriod($new_course, $request);

        return $new_course;
    }

}
