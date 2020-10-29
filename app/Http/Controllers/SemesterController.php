<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Semester;
use App\User;
use App\Setting;

class SemesterController extends Controller
{
    public function create(Semester $semester, User $user, Setting $setting, Request $request)
    {
        $targetSemester = $semester->doesExistSemester($user->id, $request->year, $request->type);
        if($targetSemester){
            $targetSemester->update([
                'year' => $request->year,
                'type' => $request->type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date
            ]);
            $setting->changeCurrentSemester($user->id, $targetSemester);
            return $targetSemester;
        }else{
            $newSemester = $semester->create([
                'user_id' => $user->id,
                'year' => $request->year,
                'type' => $request->type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date
            ]);
            $setting->changeCurrentSemester($user->id, $newSemester);
            return $newSemester;
        }
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
