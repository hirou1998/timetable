<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Course;

class CourseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function testUpdatePeriodNull()
    {
        $course = factory(Course::class)->create();
        $result = $course->updatePeriod($course, null);

        $this->assertFalse($result);
    }

}
