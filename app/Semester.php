<?php

namespace App;

class Semester extends Model
{
    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Periods()
    {
        return $this->hasMany(Period::class);
    }

    public function currentSemester($setting, $userId)
    {

        $currentSemester = $this->where('user_id', $userId)->where('year', $setting->current_year)->where('type', $setting->current_semester)->get()->first();

        return $currentSemester;
    }

    public function semesterEnum(){
        $semesterList = ['1学期', '2学期', '3学期', '通年'];
        return $semesterList;
    }

    public function doesExistSemester($userId, $year, $type){
        $semester = $this->where('user_id', $userId)->where('year', $year)->where('type', $type)->get();
        return count($semester) ? $semester->first() : false;
    }
}
