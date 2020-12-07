<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class SettingController extends Controller
{
    public function register(User $user, Request $request)
    {
        $user->setting->update([
            'first_start_time' => $request->first_start_time,
            'first_end_time' => $request->first_end_time,
            'second_start_time' => $request->second_start_time,
            'second_end_time' => $request->second_end_time,
            'third_start_time' => $request->third_start_time,
            'third_end_time' => $request->third_end_time,
            'fourth_start_time' => $request->fourth_start_time,
            'fourth_end_time' => $request->fourth_end_time,
            'fifth_start_time' => $request->fifth_start_time,
            'fifth_end_time' => $request->fifth_end_time,
            'sixth_start_time' => $request->sixth_start_time,
            'sixth_end_time' => $request->sixth_end_time,
            'university' => $request->university,
            'enter_year' => $request->enter_year,
            'graduation_year' => $request->graduation_year,
            'edited' => $request->edited
        ]);
        return $user->setting;
    }

    public function update(User $user, Request $request)
    {
        $user->setting->update([
            'first_start_time' => $request->first_start_time,
            'first_end_time' => $request->first_end_time,
            'second_start_time' => $request->second_start_time,
            'second_end_time' => $request->second_end_time,
            'third_start_time' => $request->third_start_time,
            'third_end_time' => $request->third_end_time,
            'fourth_start_time' => $request->fourth_start_time,
            'fourth_end_time' => $request->fourth_end_time,
            'fifth_start_time' => $request->fifth_start_time,
            'fifth_end_time' => $request->fifth_end_time,
            'sixth_start_time' => $request->sixth_start_time,
            'sixth_end_time' => $request->sixth_end_time
        ]);
        return $user->setting;
    }
}
