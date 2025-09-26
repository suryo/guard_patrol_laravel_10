<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\PersonController;
use App\Http\Controllers\Api\CheckpointController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\ReportController;

use App\Http\Controllers\Api\MasterController;
use App\Http\Controllers\Api\PatrolController;
use App\Http\Controllers\Api\IncidentController;


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

Route::prefix('v1')->group(function () {
    // ==== AUTH ====
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login',    [AuthController::class, 'login']);
    Route::post('auth/refresh',  [AuthController::class, 'refresh']);

    // route yang butuh token JWT
    Route::middleware('auth:api')->group(function () {
        Route::get('auth/me',     [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);

        // MASTER
        Route::get('me/schedule',  [MasterController::class, 'mySchedule']);   // ?date=YYYY-MM-DD
        Route::get('checkpoints',  [MasterController::class, 'checkpoints']);  // ?group_uid=...
        Route::get('routes',       [MasterController::class, 'routes']);       // optional

        // PATROL
        Route::post('patrol/start',              [PatrolController::class, 'start']);
        Route::post('patrol/{patrol}/scan',      [PatrolController::class, 'scan']);
        Route::post('patrol/{patrol}/finish',    [PatrolController::class, 'finish']);
        Route::get('patrol/history',             [PatrolController::class, 'history']); // ?from&to&page

        // INCIDENT
        Route::post('incident',                   [IncidentController::class, 'store']);
        Route::get('incident',                    [IncidentController::class, 'index']); // ?date
        Route::get('incident/{incident}',         [IncidentController::class, 'show']);
    });
});

// Rute untuk registrasi dan login
// Route::post('/register', 'App\Http\Controllers\AuthController@register');
// Route::post('/login', 'App\Http\Controllers\AuthController@login');

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

// Route::get('/checkpoints', [CheckpointController::class, 'index']);                      // ?scheduleId=...
Route::get('/tasks', [TaskController::class, 'index']);                                   // ?checkpointId=...&scheduleId=...

Route::post('/activity/start', [ActivityController::class, 'start']);
Route::post('/activity/end',   [ActivityController::class, 'end']);

Route::post('/report', [ReportController::class, 'store']);
