<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TbPersonController;
use App\Http\Controllers\TbCheckpointController;
use App\Http\Controllers\Ajax\PhaseAjaxController;
use App\Http\Controllers\TbTaskGroupController;
use App\Http\Controllers\SchedulePhaseActivityController;



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

Route::get('task-group', [TbTaskGroupController::class, 'manage'])
    ->name('task-group.index');

Route::prefix('phase')->group(function () {
    Route::get('{phase}/activities', [SchedulePhaseActivityController::class, 'list'])->name('phase.activities');
    Route::post('{phase}/activities', [SchedulePhaseActivityController::class, 'store'])->name('phase.activities.store');
    Route::delete('activities/{id}', [SchedulePhaseActivityController::class, 'destroy'])->name('phase.activities.destroy');
});

// resource tanpa index
Route::resource('task-group', TbTaskGroupController::class)
    ->except(['index'])
    ->parameters(['task-group' => 'task_group'])
    ->whereNumber('task_group');
Route::resource('route-guard', TbGuardController::class)
    ->parameters(['route-guard' => 'route_guard']);

Route::get('ajax/tasks', [TbTaskController::class, 'listJson'])->name('ajax.tasks');
Route::delete('/schedule-template/bulk-destroy', [TbScheduleTemplateController::class, 'bulkDestroy'])
    ->name('schedule-template.bulk-destroy');
Route::prefix('ajax')->name('ajax.')->group(function () {
    Route::get('tasks', [TbTaskGroupController::class, 'ajaxTasks'])->name('ajax.tasks');                 // list task
    Route::get('task-groups', [TbTaskGroupController::class, 'ajaxGroups'])->name('ajax.task-groups');    // list group + tasks
    Route::post('task-group/{task_group}/attach', [TbTaskGroupController::class, 'ajaxAttach'])->name('ajax.task-group.attach');   // attach many
    Route::delete('task-group/{task_group}/detach/{task}', [TbTaskGroupController::class, 'ajaxDetach'])->name('ajax.task-group.detach'); // detach one


    Route::get('schedule-group/{uid}/phases',  [PhaseAjaxController::class, 'index'])
        ->name('schedule-group.phases');                // list phases by schedule_group + date
    // opsi phase master untuk dipilih di modal
    Route::get('phases/options', [PhaseAjaxController::class, 'options'])
        ->name('phases.options');
    Route::post('schedule-group/{uid}/phases', [PhaseAjaxController::class, 'store'])
        ->name('schedule-group.phases.store');          // create phase
    // update link (ganti phase / date / urutan)
    Route::put('schedule-group-phase/{link}', [PhaseAjaxController::class, 'update'])
        ->name('schedule-group-phase.update');
    // hapus assignment phase dari group
    Route::delete('schedule-group-phase/{link}', [PhaseAjaxController::class, 'destroy'])
        ->name('schedule-group-phase.destroy');
    // Route::delete('phase/{phase}', [PhaseAjaxController::class, 'destroy'])
    //     ->name('phase.destroy');
    // delete phase
});

Route::get('schedule/assign-group', [TbScheduleController::class, 'assignGroup'])
    ->name('schedule.assign-group');              // GET ?d=YYYY-MM-DD => {selected:[]}
Route::post('schedule/assign-group', [TbScheduleController::class, 'saveAssignedGroup'])
    ->name('schedule.assign-group.save');        // POST date + group_uids[]

Route::resource('schedule', TbScheduleController::class)->parameters(['schedule' => 'schedule']);

Route::resources([
    'users'             => TbUserController::class,
    // 'schedule'          => TbScheduleController::class,
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

Route::resource('group', \App\Http\Controllers\TbGroupController::class)
    ->parameters(['group' => 'group']);

// routes/web.php



Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');


Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');

// Route::post('/login', [WebAuthController::class, 'login'])
//     ->name('login.post')
//     ->middleware('guest.web');

// Logout (auth)
Route::post('/logout', [WebAuthController::class, 'logout'])
    ->name('logout')
    ->middleware('jwt.web');
