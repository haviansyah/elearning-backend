<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassroomController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::resource('user', \App\Http\Controllers\UserController::class);
});


Route::group(['prefix' => 'classroom'], function () {
    Route::get("/",[ClassroomController::class,"getAll"]);
    Route::post('/', [ClassroomController::class,"create"]);

    Route::post("/join",[ClassroomController::class,"join"]);

    Route::post("/{id}",[ClassroomController::class,"update"]);
    Route::get("/{id}",[ClassroomController::class,"getOnly"]);
    Route::delete("/{id}",[ClassroomController::class,"delete"]);

});


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

    Route::post('login', [AuthController::class,"login"])->name("login");
    Route::post('logout', [AuthController::class,"logout"]);
    Route::post('refresh', [AuthController::class,"refresh"]);
    Route::get('me', [AuthController::class,"me"]);

});
