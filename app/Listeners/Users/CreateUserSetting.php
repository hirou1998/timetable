<?php

namespace App\Listeners\Users;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateUserSetting
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // $todayMonth = date('n');
        // $todayYear = date('Y');
        // //登録日時によってデフォルトの開始日を決定
        // $todayMonth <= 8 ? $start = date('Y-m-d', mktime(0,0,0,4,8,$todayYear)) : $start = date('Y-m-d', mktime(0,0,0,9,27,$todayYear));
        // //登録日時によってデフォルトの終了日を決定
        // $todayMonth <= 8 ? $start = date('Y-m-d', mktime(0,0,0,4,8,$todayYear)) : $start = date('Y-m-d', mktime(0,0,0,9,27,$todayYear));

        $event->user->setting()->create();
    }
}
