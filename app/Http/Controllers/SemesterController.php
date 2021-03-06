<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Semester;
use App\User;
use App\Setting;

class SemesterController extends Controller
{

    public function store(Semester $semester, User $user, Request $request)
    {
        $inputYear = $request->year;
        $inputType = $request->type;
        $alreadyExist = $semester->doesExistSemester($user->id, $inputYear, $inputType);

        if(!$alreadyExist){
            $item = $semester->create([
                'year' => $inputYear,
                'type' => $inputType,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'user_id' => $user->id
            ]);
            return $item;
        }else{
            return Response::json(['error' => 'すでに登録されている学期は追加できません。'], 400);
        }
    }


    public function change(Semester $semester, Setting $setting)
    {
        $setting->changeCurrentSemester(auth()->user()->id, $semester);
    }

    public function edit(Semester $semester, Request $request)
    {
        return $semester->update([
            'year' => $request->year,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);
        return $semester;
    }

    public function destroy(Semester $semester)
    {
        $semester->delete();
        return response()->json([]);
    }
}
