<?php

namespace App;

class Type extends Model
{
    public static function index()
    {
        return static::first();
    }

    public static function createIfNotExist()
    {
        $record = static::index();

        if($record){
            return false;
        }else{
            static::create();
        }
    }
}
