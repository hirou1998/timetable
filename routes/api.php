<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => ['auth:api']], function(){
    Route::get('/user', function(Request $request){
        return $request->user();
    });
    Route::get('period/{user}/{semester}', 'Api\PeriodController@index');
    Route::get('course/detail', 'Api\CourseController@index');
    Route::get('course/assignments/c/{course}', 'Api\AssignmentController@sortByCourse');
    Route::get('course/assignments/u/{user}', 'Api\AssignmentController@sortByUser');
    Route::get('/user/setting/{user}', 'Api\SettingController@index');
    Route::get('events/{user}', 'Api\EventController@index');
    Route::get('setting/semester/detail/{user}', 'Api\SemesterController@index');
    Route::get('setting/semester/enum', 'Api\SemesterController@semesterEnum');
});

Route::get('setting/semester/current/{user}', 'Api\SemesterController@currentSemester');
