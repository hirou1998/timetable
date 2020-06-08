<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Course;
use App\Period;
use App\Setting;
use App\Assignment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user1 = factory(User::class)->create([
            'name' => 'hiromu',
            'email' => 'tanahi1025@gmail.com'
        ]);
        $user2 = factory(User::class)->create([
            'name' => 'hana',
            'email' => 'hana@hana.com'
        ]);
        $user3 = factory(User::class)->create([
            'name' => 'shotaro',
            'email' => 'taro@taro.com'
        ]);

        $course1 = factory(Course::class)->create([
            'name' => 'Spanish01',
            'user_id' => $user1->id,
        ]);

        $course2 = factory(Course::class)->create([
            'name' => 'British studies',
            'user_id' => $user1->id,
        ]);

        $course3 = factory(Course::class)->create([
            'name' => 'Administration',
            'user_id' => $user1->id,
        ]);

        $course4 = factory(Course::class)->create([
            'name' => 'Internatinal Politics',
            'user_id' => $user1->id,
        ]);

        $course5 = factory(Course::class)->create([
            'name' => 'European politics',
            'user_id' => $user1->id,
        ]);

        $period1 = factory(Period::class)->create([
            'course_id' => $course1->id,
            'user_id' => $user1->id,
            'day_of_week' => 1,
            'period' => 2,
        ]);

        $period2 = factory(Period::class)->create([
            'course_id' => $course2->id,
            'user_id' => $user1->id,
            'day_of_week' => 1,
            'period' => 4,
        ]);

        $period3 = factory(Period::class)->create([
            'course_id' => $course3->id,
            'user_id' => $user1->id,
            'day_of_week' => 2,
            'period' => 2,
        ]);

        $period4 = factory(Period::class)->create([
            'course_id' => $course4->id,
            'user_id' => $user1->id,
            'day_of_week' => 4,
            'period' => 4,
        ]);

        $period5 = factory(Period::class)->create([
            'course_id' => $course4->id,
            'user_id' => $user1->id,
            'day_of_week' => 3,
            'period' => 3,
        ]);

        $period6 = factory(Period::class)->create([
            'course_id' => $course1->id,
            'user_id' => $user1->id,
            'day_of_week' => 3,
            'period' => 2,
        ]);

        $setting1 = factory(Setting::class)->create([
            'user_id' => $user1->id
        ]);

        $assignment1 = factory(Assignment::class)->create([
            'user_id' => $user1->id,
            'course_id' => $course1->id,
            'body' => 'read a textbook',
            'date' => '2020-04-13',
        ]);
    }
}
