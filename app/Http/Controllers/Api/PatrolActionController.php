<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\TbActivity;
use App\Models\TbActivityTask;

class PatrolActionController extends Controller
{
    /**
     * POST /api/v1/patrols/start
     * Body:
     * {
     *   "schedule_date":"2025-09-18",
     *   "group_uid":1,
     *   "phase_uid":11,
     *   "person_id":"EMP001",
     *   "checkpoint_start":"CP-01"     // optional
     * }
     * - Validasi jadwal (tanggal+group+phase).
     * - Buat 1 baris tb_activities (status: started).
     * - Generate tb_activity_task dari daftar task pada jadwal phase tsb (bisa nol).
     */
    public function start(Request $r)
    {
        $data = $r->validate([
            'schedule_date'    => 'required|date_format:Y-m-d',
            'group_uid'        => 'required|integer',
            'phase_uid'        => 'required|integer',
            'person_id'        => 'required|string|max:100',
            'checkpoint_start' => 'nullable|string|max:100',
        ]);

        // temukan schedule dan task yang relevan (alias grp untuk hindari reserved word)
        $base = DB::table('tb_schedules as schedule')
            ->join('tb_schedule_group as schedule_group','schedule_group.schedule_uid','=','schedule.uid')
            ->join('tb_groups as grp','schedule_group.group_uid','=','grp.uid')
            ->leftJoin('tb_schedule_group_phase as sg_phase','schedule_group.uid','=','sg_phase.schedule_group_uid')
            ->leftJoin('tb_schedule_group_phase_activity as sgp','sgp.schedule_group_phase_uid','=','sg_phase.uid')
            ->where('schedule.scheduleDate',$data['schedule_date'])
            ->where('schedule_group.group_uid',(int)$data['group_uid'])
            ->where('sg_phase.phase_uid',(int)$data['phase_uid']);

        $meta = $base->clone()
            ->select('schedule.uid as schedule_uid','sg_phase.uid as sg_phase_uid')
            ->first();

        if (!$meta) {
            return response()->json(['ok'=>false,'message'=>'Jadwal/phase tidak ditemukan'],404);
        }

        $taskIds = $base->clone()
            ->whereNotNull('sgp.task_uid')
            ->orderBy('sgp.sortOrder')
            ->pluck('sgp.task_uid')
            ->unique()->values()->all();

        // cegah double start oleh orang yang sama (belum selesai) pada phase & date & schedule sama
        $existing = TbActivity::query()
            ->where('phase_uid',(int)$data['phase_uid'])
            ->where('personId',$data['person_id'])
            ->where('scheduleId',(string)$meta->schedule_uid)
            ->whereNull('activityEnd')
            ->first();

        if ($existing) {
            return response()->json(['ok'=>true,'warning'=>'Aktivitas belum diselesaikan','activity'=>$existing],200);
        }

        $now = Carbon::now();

        $activity = DB::transaction(function () use ($data, $meta, $taskIds, $now) {
            $activity = TbActivity::create([
                'activityId'      => (string) Str::uuid(),
                'phase_uid'       => (int)$data['phase_uid'],
                'personId'        => $data['person_id'],
                'scheduleId'      => (string)$meta->schedule_uid,
                'checkpointStart' => $data['checkpoint_start'] ?? null,
                'checkpointEnd'   => null,
                'activityStart'   => $now,
                'activityEnd'     => null,
                'activityStatus'  => 'started',
                'lastUpdated'     => $now,
            ]);

            foreach ($taskIds as $tid) {
                TbActivityTask::create([
                    'activity_uid' => $activity->uid,
                    'task_uid'     => (int)$tid,
                    'is_done'      => 0,
                    'checked_at'   => null,
                    'notes'        => null,
                ]);
            }
            return $activity;
        });

        return response()->json([
            'ok'=>true,
            'activity'=>$activity->fresh(),
            'tasks'=>TbActivityTask::where('activity_uid',$activity->uid)->get(),
        ],201);
    }

    /**
     * PATCH /api/v1/patrols/{activity}/tasks
     * Body:
     * {
     *   "updates":[
     *      {"task_uid":2,"is_done":true,"notes":"lampu sudah mati"},
     *      {"task_uid":1,"is_done":false}
     *   ]
     * }
     * - Bulk update status tugas pada 1 aktivitas.
     * - Jika ada task_uid yang belum ada row-nya tapi memang termasuk jadwal phase ini,
     *   barisnya akan dibuat terlebih dahulu.
     */
    public function bulkTasks(Request $r, TbActivity $activity)
    {
        $pay = $r->validate([
            'updates' => 'required|array|min:1',
            'updates.*.task_uid' => 'required|integer',
            'updates.*.is_done'  => 'required|boolean',
            'updates.*.notes'    => 'nullable|string',
        ]);

        // daftar task yang sah dari jadwal untuk phase & schedule ini
        $validTaskIds = DB::table('tb_schedule_group_phase as sgp')
            ->join('tb_schedule_group_phase_activity as a','a.schedule_group_phase_uid','=','sgp.uid')
            ->where('sgp.phase_uid',$activity->phase_uid)
            ->join('tb_schedules as s','s.uid','=','sgp.schedule_group_uid') // bukan langsung, tetapi kita hanya butuh validasi task; jika struktur berbeda, abaikan validasi ini
            ->pluck('a.task_uid')->unique()->filter()->values()->all();

        $now = Carbon::now();

        DB::transaction(function () use ($activity, $pay, $validTaskIds, $now) {
            foreach ($pay['updates'] as $u) {
                // pastikan row ada
                $row = TbActivityTask::firstOrCreate(
                    ['activity_uid'=>$activity->uid,'task_uid'=>$u['task_uid']],
                    ['is_done'=>0,'checked_at'=>null,'notes'=>null]
                );

                // (opsional) jika ingin ketat, batasi hanya task yang valid sesuai jadwal
                if (!empty($validTaskIds) && !in_array($u['task_uid'], $validTaskIds)) {
                    continue; // skip task asing
                }

                $row->is_done   = $u['is_done'] ? 1 : 0;
                $row->checked_at= $u['is_done'] ? $now : null;
                if (array_key_exists('notes',$u)) $row->notes = $u['notes'];
                $row->save();
            }

            $activity->update(['lastUpdated'=>$now]);
        });

        return response()->json([
            'ok'=>true,
            'activity'=>$activity->fresh(),
            'tasks'=>TbActivityTask::where('activity_uid',$activity->uid)->get(),
        ]);
    }

    /**
     * PATCH /api/v1/patrols/{activity}/tasks/{task_uid}
     * Body: { "is_done": true, "notes": "cek selesai" }
     */
    public function checkTask(Request $r, TbActivity $activity, int $task_uid)
    {
        $d = $r->validate([
            'is_done' => 'required|boolean',
            'notes'   => 'nullable|string',
        ]);

        $now = Carbon::now();

        $row = TbActivityTask::firstOrCreate(
            ['activity_uid'=>$activity->uid,'task_uid'=>$task_uid],
            ['is_done'=>0,'checked_at'=>null,'notes'=>null]
        );

        $row->is_done = $d['is_done'] ? 1 : 0;
        $row->checked_at = $d['is_done'] ? $now : null;
        if ($r->filled('notes')) $row->notes = $d['notes'];
        $row->save();

        $activity->update(['lastUpdated'=>$now]);

        return response()->json(['ok'=>true,'task'=>$row]);
    }

    /**
     * PATCH /api/v1/patrols/{activity}/finish
     * Body: { "checkpoint_end":"CP-02", "set_all_done": true }
     */
    public function finish(Request $r, TbActivity $activity)
    {
        $r->validate([
            'checkpoint_end' => 'nullable|string|max:100',
            'set_all_done'   => 'nullable|boolean',
        ]);

        if ($activity->activityEnd) {
            return response()->json(['ok'=>true,'message'=>'Aktivitas sudah selesai','activity'=>$activity],200);
        }

        DB::transaction(function () use ($r, $activity) {
            $now = Carbon::now();

            if ($r->boolean('set_all_done')) {
                TbActivityTask::where('activity_uid',$activity->uid)
                    ->where('is_done',0)
                    ->update(['is_done'=>1,'checked_at'=>$now]);
            }

            $activity->update([
                'checkpointEnd'  => $r->input('checkpoint_end', $activity->checkpointEnd),
                'activityEnd'    => $now,
                'activityStatus' => 'finished',
                'lastUpdated'    => $now,
            ]);
        });

        return response()->json([
            'ok'=>true,
            'activity'=>$activity->fresh(),
            'tasks'=>TbActivityTask::where('activity_uid',$activity->uid)->get(),
        ]);
    }

    /** GET /api/v1/patrols/{activity} */
    public function show(TbActivity $activity)
    {
        return response()->json([
            'ok'=>true,
            'activity'=>$activity,
            'tasks'=>TbActivityTask::where('activity_uid',$activity->uid)->orderBy('checked_at')->get(),
        ]);
    }
}
