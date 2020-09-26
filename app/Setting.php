<?php

namespace App;

class Setting extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCurrentSemester($setting)
    {
        $currentYear = $setting->current_year;
        $currentSemester = $setting->current_semester;

        $semester = Semester::where('year', $currentYear)->where('type', $currentSemester)->get()->first();

        return $semester;
    }

    public function changeCurrentSemester($userId, $newCurrentSemester)
    {
        $setting = $this->where('user_id', $userId)->get()->first();
        $setting->update([
            'current_year' => $newCurrentSemester->year,
            'current_semester' => $newCurrentSemester->type
        ]);
    }
}
