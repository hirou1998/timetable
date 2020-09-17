<?php

namespace App;

class Setting extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCurrentSemesterId($setting)
    {
        $currentYear = $setting->current_year;
        $currentSemester = $setting->current_semester;

        $semester = Semester::where('year', $currentYear)->where('type', $currentSemester)->get()->first();

        return $semester->id;
    }
}
