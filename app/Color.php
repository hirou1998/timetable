<?php

namespace App;

class Color extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }
}
