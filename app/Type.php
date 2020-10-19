<?php

namespace App;

class Type extends Model
{
    public function index()
    {
        return $this->first();
    }
}
