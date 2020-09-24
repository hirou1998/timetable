<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Course;
use App\Period;
use App\Setting;
use App\Assignment;
use App\Semester;

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
        // $user2 = factory(User::class)->create([
        //     'name' => 'hana',
        //     'email' => 'hana@hana.com'
        // ]);
        // $user3 = factory(User::class)->create([
        //     'name' => 'shotaro',
        //     'email' => 'taro@taro.com'
        // ]);

        $course1 = factory(Course::class)->create([
            'name' => '東アジア論',
            'user_id' => $user1->id,
        ]);

        $course2 = factory(Course::class)->create([
            'name' => '比較行政学',
            'user_id' => $user1->id,
        ]);

        $course3 = factory(Course::class)->create([
            'name' => 'データで読み解く国際紛争',
            'user_id' => $user1->id,
        ]);

        $course4 = factory(Course::class)->create([
            'name' => '科学技術を考える',
            'user_id' => $user1->id,
        ]);

        // $course5 = factory(Course::class)->create([
        //     'name' => 'European politics',
        //     'user_id' => $user1->id,
        // ]);

        $semester1 = factory(Semester::class)->create([
            'user_id' => $user1->id,
            'year' => '2020',
            'type' => '2学期',
            'start_date' => '2020/09/25',
            'end_date' => '2021/02/01'
        ]);

        $period1 = factory(Period::class)->create([
            'course_id' => $course1->id,
            'user_id' => $user1->id,
            'semester_id' => $semester1->id,
            'day_of_week' => 2,
            'period' => 2,
        ]);

        $period2 = factory(Period::class)->create([
            'course_id' => $course2->id,
            'user_id' => $user1->id,
            'semester_id' => $semester1->id,
            'day_of_week' => 2,
            'period' => 3,
        ]);

        $period3 = factory(Period::class)->create([
            'course_id' => $course2->id,
            'user_id' => $user1->id,
            'semester_id' => $semester1->id,
            'day_of_week' => 4,
            'period' => 3,
        ]);

        $period4 = factory(Period::class)->create([
            'course_id' => $course3->id,
            'user_id' => $user1->id,
            'semester_id' => $semester1->id,
            'day_of_week' => 4,
            'period' => 2,
        ]);

        $period5 = factory(Period::class)->create([
            'course_id' => $course4->id,
            'user_id' => $user1->id,
            'semester_id' => $semester1->id,
            'day_of_week' => 4,
            'period' => 4,
        ]);

        // $period6 = factory(Period::class)->create([
        //     'course_id' => $course1->id,
        //     'user_id' => $user1->id,
        //     'day_of_week' => 3,
        //     'period' => 2,
        // ]);

        $setting1 = factory(Setting::class)->create([
            'user_id' => $user1->id,
            'university' => '早稲田'
        ]);

        $assignment1 = factory(Assignment::class)->create([
            'user_id' => $user1->id,
            'course_id' => $course1->id,
            'body' => 'read a textbook',
            'date' => '2020/04/13',
            'memo' => 'text page 10 to 20'
        ]);
        $assignment2 = factory(Assignment::class)->create([
            'user_id' => $user1->id,
            'course_id' => $course1->id,
            'body' => 'five quizes',
            'date' => '2020/04/15',
            'done_flg' => true,
            'memo' => 'words quiz'
        ]);
    }
}
