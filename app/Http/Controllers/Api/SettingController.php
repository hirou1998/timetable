<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Setting;
use App\User;

class SettingController extends Controller
{
    public function index(Setting $setting, User $user)
    {
        $settingData = $setting->where('user_id', $user->id)->get()->first();
        return [
            'id' => $settingData->id,
            'user_id' => $settingData->user_id,
            'periods' => [
                [
                    date('G:i', strtotime($settingData->first_start_time)), 
                    date('G:i', strtotime($settingData->first_end_time))
                ],
                [
                    date('G:i', strtotime($settingData->second_start_time)), 
                    date('G:i', strtotime($settingData->second_end_time))
                ],
                [
                    date('G:i', strtotime($settingData->third_start_time)), 
                    date('G:i', strtotime($settingData->third_end_time))
                ],
                [
                    date('G:i', strtotime($settingData->fourth_start_time)), 
                    date('G:i', strtotime($settingData->fourth_end_time))
                ],
                [
                    date('G:i', strtotime($settingData->fifth_start_time)), 
                    date('G:i', strtotime($settingData->fifth_end_time))
                ],
                [
                    date('G:i', strtotime($settingData->sixth_start_time)), 
                    date('G:i', strtotime($settingData->sixth_end_time))
                ],
            ],
            'start_date' => $settingData->start_date,
            'end_date' => $settingData->end_date,
        ];
    }
}
