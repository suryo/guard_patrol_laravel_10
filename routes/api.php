<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\PersonController;
use App\Http\Controllers\Api\CheckpointController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rute untuk registrasi dan login
Route::post('/register', 'App\Http\Controllers\AuthController@register');
Route::post('/login', 'App\Http\Controllers\AuthController@login');

// Rute yang dilindungi oleh middleware JWT
Route::middleware('jwt.auth')->group(function () {
    Route::get('/profile', 'App\Http\Controllers\AuthController@profile');
});

Route::get('/userr', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/schedules', [ScheduleController::class, 'index']);                           // ?date=YYYY-MM-DD
Route::get('/person/by-card/{uid}', [PersonController::class, 'byCardUid']);             // NFC UID

Route::get('/checkpoints', [CheckpointController::class, 'index']);                      // ?scheduleId=...
Route::get('/tasks', [TaskController::class, 'index']);                                   // ?checkpointId=...&scheduleId=...

Route::post('/activity/start', [ActivityController::class, 'start']);
Route::post('/activity/end',   [ActivityController::class, 'end']);

Route::post('/report', [ReportController::class, 'store']);
