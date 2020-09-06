<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CourseControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestView()
    {
        $response = $this->get(route('timetable.index'));

        $response->assertRedirect(route('login'));
    }

    public function testAuthView()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('timetable.index'));

        $response->assertStatus(200)
            ->assertViewIs('timetable.index');
    }
}
