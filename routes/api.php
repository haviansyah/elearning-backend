<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\Quiz;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::resource('user', \App\Http\Controllers\UserController::class);
});


Route::group(['prefix' => 'classroom',"middleware"=>"jwtAuth"], function () {
    Route::get("/",[ClassroomController::class,"getAll"]);
    Route::post('/', [ClassroomController::class,"create"]);

    Route::post("/join",[ClassroomController::class,"join"]);

    Route::post("/{id}",[ClassroomController::class,"update"]);
    Route::get("/{id}",[ClassroomController::class,"getOnly"]);
    Route::delete("/{id}",[ClassroomController::class,"delete"]);

});

Route::group(['prefix' => 'quiz',"middleware" => "jwtAuth"], function () {
    Route::post('/', [Quiz::class,"store"]);
    Route::get('/', [Quiz::class,"index"]);
    Route::get('/{id}', [Quiz::class,"getOne"]);

    Route::post('/{id}/attempt',[Quiz::class,"post_attempt"]);
    Route::get('/{id}/attempt',[Quiz::class,"get_attempt"]);

    Route::get('/attempt/{id}',[Quiz::class,"get_detail_attempt"]);
    Route::post('/attempt/{id}',[Quiz::class,"insert_poin"]);

    Route::get('/calculate/{id}',[Quiz::class,"calculate_poin_multiple_choice"]);
    Route::get('/calculate2/{id}',[Quiz::class,"calculate_poin_match_pair"]);
    
});

Route::group(['prefix' => 'lesson',"middleware" => "jwtAuth"], function () {
    Route::post('/', [LessonController::class,"store"]);
    Route::get('/{id}', [LessonController::class,"show"]);
    Route::post('/{id}', [LessonController::class,"update"]);
});



Route::group(['prefix' => 'task',"middleware" => "jwtAuth"], function () {
    Route::post("/",[TaskController::class,"store"]);
    Route::get("/",[TaskController::class,"index"]);
    Route::get("/{id}",[TaskController::class,"get"]);
    Route::post("/{id}",[TaskController::class,"update"]);

    Route::post("/{id}/attempt",[TaskController::class,"new_attempt"]);
    Route::get("/{id}/attempt",[TaskController::class,"show_attempt"]);
    Route::get("/attempt/{id}",[TaskController::class,"detail_attempt"]);
    Route::post("/attempt/{id}",[TaskController::class,"insert_poin"]);
});

Route::group(['prefix' => 'calendar',"middleware" => "jwtAuth"], function () {
    Route::get("/",[CalendarController::class,"get_event_by_classroom"]);
});


Route::group(['prefix' => 'grade',"middleware" => "jwtAuth"], function () {
    Route::get("/{id}",[GradeController::class,"get_grade_by_classroom"]);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

    Route::post('login', [AuthController::class,"login"])->name("login");

    Route::group(['middleware' => ['jwtAuth']], function () {
        Route::post('logout', [AuthController::class,"logout"]);
        Route::post('refresh', [AuthController::class,"refresh"]);
        Route::get('me', [AuthController::class,"me"]);
    });

}); 


Route::get("/test",function(){
    $attempt = \App\Models\QuizAttempt::all()->last();
    return $attempt->Poin;
});

Route::get("/dashboard",[DashboardController::class,"index"])->middleware(["jwtAuth"]);
