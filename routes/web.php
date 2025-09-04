<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TbPersonController;
use App\Http\Controllers\TbCheckpointController;

use App\Http\Controllers\{
    TbUserController,
    TbScheduleController,
    TbActivityController,
    TbReportController,
    TbReportAsliController,
    TbScheduleTemplateController,
    TbTaskController,
    TbTaskTemplateController,
    TbTaskListController,
    TbPersonMappingController,
    TbPhaseController,
    TbLogsController,
    LaporanController
};

use App\Http\Controllers\WebAuthController;

use App\Http\Controllers\TbGuardController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', fn() => redirect()->route('person.index'));
Route::resource('person', TbPersonController::class)->parameters(['person' => 'person']);
Route::resource('checkpoint', TbCheckpointController::class)->parameters(['checkpoint' => 'checkpoint']);

Route::resource('route-guard', TbGuardController::class)
     ->parameters(['route-guard' => 'route_guard']);

Route::resources([
    'users'             => TbUserController::class,
    'schedule'          => TbScheduleController::class,
    'activity'          => TbActivityController::class,
    'report'            => TbReportController::class,
    'schedule-template' => TbScheduleTemplateController::class,
    'task'              => TbTaskController::class,
    'task-template'     => TbTaskTemplateController::class,
    'task-list'         => TbTaskListController::class,
    'person-mapping'    => TbPersonMappingController::class,
    'phase'             => TbPhaseController::class,
    'logs'              => TbLogsController::class,
]);

Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');


Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');

// Route::post('/login', [WebAuthController::class, 'login'])
//     ->name('login.post')
//     ->middleware('guest.web');

// Logout (auth)
Route::post('/logout', [WebAuthController::class, 'logout'])
    ->name('logout')
    ->middleware('jwt.web');
