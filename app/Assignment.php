<?php

namespace App;

class Assignment extends Model
{
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
