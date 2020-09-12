<?php

namespace App;

class Setting extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
