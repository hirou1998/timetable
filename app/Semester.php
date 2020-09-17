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
}
