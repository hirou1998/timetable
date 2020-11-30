<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Type;

class TypeController extends Controller
{
    public function index(Type $type)
    {
        $list = $type->index();
        return [
            $list->type1,
            $list->type2,
            $list->type3,
            $list->type4
        ];
    }
}
