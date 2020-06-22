<?php

namespace App;

class Assignment extends Model
{
    protected $with = ['course'];
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
