<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/register', function(){
    return view('auth.register');
})->name('register');
Route::get('/login', function(){
    return view('auth.login');
})->name('login');
Route::get('/', function () {
    return view('top.index');
});

Route::post('/register', 'Auth\RegisterController@register');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::middleware(['auth'])->group(function(){
    Route::get('/timetable', function(){
        return view('timetable.index');
    });
    Route::get('/timetable/detail', function(){
        return view('timetable.detail');
    });
    Route::get('/setting', function(){
        return view('timetable.index');
    });
    Route::get('/calendar', function(){
        return view('timetable.index');
    });
    Route::get('/calendar/detail', function(){
        return view('timetable.index');
    });
    Route::post('/course/update/{course}', 'CourseController@update');
    Route::post('/course/register/{user}', 'CourseController@store');
    Route::put('/course/color/{course}', 'CourseController@changeColor');
    Route::delete('/period/{period}', 'PeriodController@destroy');
    Route::resource('/{course}/assignment', 'AssignmentController');
    Route::resource('/{user}/event', 'EventController');
    Route::get('/setting/color', function(){
        return view('timetable.index');
    });
    Route::get('/setting/period', function(){
        return view('timetable.index');
    });
    Route::get('/setting/user', function(){
        return view('timetable.index');
    });
    Route::get('/setting/user/password-reset', function(){
        return view('timetable.index');
    });
    Route::get('/setting/mypage', function(){
        return view('timetable.index');
    });
    Route::put('/setting/period/{user}', 'SettingController@update');
});