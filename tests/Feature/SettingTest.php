<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Setting;
use App\Semester;
use App\User;

class SettingTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function testChangeCurrentSemesterNull()
    {
        $user = factory(User::class)->create();
        $semester = factory(Semester::class)->create();
        $setting = factory(Setting::class)->create();
        $setting->update([
            'user_id' => $user->id
        ]);

        $result = $setting->changeCurrentSemester($user->id, null);

        $this->assertFalse($result);
    }
}
