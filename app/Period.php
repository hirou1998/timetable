<?php

namespace App;

class Period extends Model
{
    protected $with = ['course'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // public function semester()
    // {
    //     return $this->belongsTo(Semester::class);
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
