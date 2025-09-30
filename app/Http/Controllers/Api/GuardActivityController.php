<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\TbActivity;
use App\Models\TbActivityTask;

class GuardActivityController extends Controller
{
    /**
     * GET /api/v1/scheduled-tasks
     * Query: schedule_date=YYYY-MM-DD&group_uid=1&phase_uid=123 (phase optional)
     * Mengembalikan daftar task dari jadwal untuk validasi di client.
     */
    public function scheduledTasks(Request $r)
    {
        $r->validate([
            'schedule_date' => 'required|date_format:Y-m-d',
            'group_uid'     => 'required|integer',
            'phase_uid'     => 'nullable|integer',
        ]);

        $q = DB::table('tb_schedules as schedule')
            ->join('tb_schedule_group as schedule_group', 'schedule_group.schedule_uid', '=', 'schedule.uid')
            ->join('tb_groups as `group`', 'schedule_group.group_uid', '=', 'group.uid')
            ->leftJoin('tb_schedule_group_phase as sg_phase', 'schedule_group.uid', '=', 'sg_phase.schedule_group_uid')
            ->leftJoin('tb_phases as phase', 'sg_phase.phase_uid', '=', 'phase.uid')
            ->leftJoin('tb_schedule_group_phase_activity as sgp_activity', 'sgp_activity.schedule_group_phase_uid', '=', 'sg_phase.uid')
            ->leftJoin('tb_task_group as tg', 'sgp_activity.task_group_uid', '=', 'tg.uid')
            ->leftJoin('tb_task as task', 'task.uid', '=', 'sgp_activity.task_uid')
            ->where('schedule.scheduleDate', $r->schedule_date)
            ->where('schedule_group.group_uid', (int)$r->group_uid)
            ->orderBy('phase.phaseId')
            ->orderBy('sgp_activity.sortOrder');

        if ($r->filled('phase_uid')) {
            $q->where('sg_phase.phase_uid', (int)$r->phase_uid);
        }

        $rows = $q->get([
            'schedule.uid as schedule_uid',
            'schedule.scheduleDate',
            'group.groupName',
            'sg_phase.uid as schedule_group_phase_uid',
            'sg_phase.phaseDate',
            'phase.uid as phase_uid',
            'phase.phaseId',
            'tg.uid as task_group_uid',
            'tg.groupName as taskgroup',
            'task.uid as task_uid',
            'task.taskName',
            'sgp_activity.sortOrder',
            'sgp_activity.activityNote',
        ]);

        return response()->json([
            'ok' => true,
            'data' => $rows,
        ]);
    }

    /**
     * POST /api/v1/activities/start
     * Body JSON:
     * {
     *   "schedule_date": "2025-09-18",
     *   "group_uid": 1,
     *   "phase_uid": 123,
     *   "person_id": "EMP001",         // ID petugas
     *   "checkpoint_start": "CP-01",   // optional
     *   "notes": "mulai patroli",      // optional
     *   "mark_done_task_ids": [11,22]  // optional: task.uid yang langsung ditandai selesai
     * }
     *
     * Proses:
     * 1) Validasi jadwal & ambil list task (sgp_activity.task_uid) untuk phase tersebut.
     * 2) Insert tb_activities (activityStart = now, activityStatus = 'started').
     * 3) Insert tb_activity_task untuk setiap task dari jadwal (is_done=0),
     *    lalu jika ada "mark_done_task_ids", tandai done saat itu.
     */
    public function start(Request $r)
    {
        $payload = $r->validate([
            'schedule_date'      => 'required|date_format:Y-m-d',
            'group_uid'          => 'required|integer',
            'phase_uid'          => 'required|integer',
            'person_id'          => 'required|string|max:100',
            'checkpoint_start'   => 'nullable|string|max:100',
            'notes'              => 'nullable|string',
            'mark_done_task_ids' => 'nullable|array',
            'mark_done_task_ids.*' => 'integer',
        ]);

        // 1) Temukan schedule_uid & tasks yang valid untuk tanggal+group+phase
        $base = DB::table('tb_schedules as schedule')
            ->join('tb_schedule_group as schedule_group', 'schedule_group.schedule_uid', '=', 'schedule.uid')
            ->join('tb_schedule_group_phase as sg_phase', 'schedule_group.uid', '=', 'sg_phase.schedule_group_uid')
            ->leftJoin('tb_schedule_group_phase_activity as sgp_activity', 'sgp_activity.schedule_group_phase_uid', '=', 'sg_phase.uid')
            ->where('schedule.scheduleDate', $payload['schedule_date'])
            ->where('schedule_group.group_uid', (int)$payload['group_uid'])
            ->where('sg_phase.phase_uid', (int)$payload['phase_uid']);

        $schedule = $base->clone()
            ->select('schedule.uid as schedule_uid', 'sg_phase.uid as schedule_group_phase_uid')
            ->first();

        if (!$schedule) {
            return response()->json(['ok' => false, 'message' => 'Jadwal/phase tidak ditemukan'], 404);
        }

        $taskIds = $base->clone()
            ->whereNotNull('sgp_activity.task_uid')
            ->orderBy('sgp_activity.sortOrder')
            ->pluck('sgp_activity.task_uid')
            ->unique()
            ->values()
            ->all();

        // 2) Cegah duplikasi aktivitas terbuka (belum finish) untuk orang & phase di tanggal tsb
        $existing = TbActivity::query()
            ->where('phase_uid', (int)$payload['phase_uid'])
            ->where('personId', $payload['person_id'])
            ->where('scheduleId', (string)$schedule->schedule_uid)
            ->whereNull('activityEnd')
            ->first();

        if ($existing) {
            return response()->json([
                'ok' => true,
                'warning' => 'Aktivitas sebelumnya belum diselesaikan',
                'activity' => $existing,
            ], 200);
        }

        // 3) Simpan activity + activity_task
        $activity = DB::transaction(function () use ($payload, $schedule, $taskIds) {
            $now = Carbon::now();

            $activity = TbActivity::create([
                'activityId'      => (string) Str::uuid(),
                'phase_uid'       => (int) $payload['phase_uid'],
                'personId'        => $payload['person_id'],
                'scheduleId'      => (string) $schedule->schedule_uid,
                'checkpointStart' => $payload['checkpoint_start'] ?? null,
                'checkpointEnd'   => null,
                'activityStart'   => $now,
                'activityEnd'     => null,
                'activityStatus'  => 'started',
                'lastUpdated'     => $now,
            ]);

            // Insert activity_task dari jadwal
            foreach ($taskIds as $tid) {
                TbActivityTask::create([
                    'activity_uid' => $activity->uid,
                    'task_uid'     => (int) $tid,
                    'is_done'      => 0,
                    'checked_at'   => null,
                    'notes'        => null,
                ]);
            }

            // Tandai done jika diminta
            if (!empty($payload['mark_done_task_ids'])) {
                TbActivityTask::where('activity_uid', $activity->uid)
                    ->whereIn('task_uid', $payload['mark_done_task_ids'])
                    ->update([
                        'is_done'    => 1,
                        'checked_at' => $now,
                    ]);
            }

            return $activity;
        });

        return response()->json([
            'ok' => true,
            'activity' => $activity->fresh(),
            'tasks' => TbActivityTask::where('activity_uid', $activity->uid)->get(),
        ], 201);
    }

    /**
     * PATCH /api/v1/activities/{activity}/finish
     * Body JSON: { "checkpoint_end": "CP-02", "notes": "selesai", "set_all_done": true }
     */
    public function finish(Request $r, TbActivity $activity)
    {
        $r->validate([
            'checkpoint_end' => 'nullable|string|max:100',
            'notes'          => 'nullable|string',
            'set_all_done'   => 'nullable|boolean',
        ]);

        if ($activity->activityEnd) {
            return response()->json(['ok' => true, 'message' => 'Aktivitas sudah selesai'], 200);
        }

        DB::transaction(function () use ($r, $activity) {
            $now = Carbon::now();

            if ($r->boolean('set_all_done')) {
                TbActivityTask::where('activity_uid', $activity->uid)
                    ->where('is_done', 0)
                    ->update(['is_done' => 1, 'checked_at' => $now]);
            }

            $activity->update([
                'checkpointEnd'  => $r->input('checkpoint_end', $activity->checkpointEnd),
                'activityEnd'    => $now,
                'activityStatus' => 'finished',
                'lastUpdated'    => $now,
            ]);
        });

        return response()->json([
            'ok' => true,
            'activity' => $activity->fresh(),
            'tasks' => TbActivityTask::where('activity_uid', $activity->uid)->get(),
        ]);
    }

    /**
     * PATCH /api/v1/activities/{activity}/tasks/{task}
     * Body JSON: { "is_done": true, "notes": "cek air selesai" }
     */
    public function checkTask(Request $r, TbActivity $activity, int $task)
    {
        $data = $r->validate([
            'is_done' => 'required|boolean',
            'notes'   => 'nullable|string',
        ]);

        $row = TbActivityTask::where('activity_uid', $activity->uid)
            ->where('task_uid', $task)
            ->first();

        if (!$row) {
            return response()->json(['ok' => false, 'message' => 'Task tidak ditemukan pada aktivitas ini'], 404);
        }

        $row->is_done = $data['is_done'] ? 1 : 0;
        $row->checked_at = $data['is_done'] ? Carbon::now() : null;
        if ($r->filled('notes')) $row->notes = $data['notes'];
        $row->save();

        return response()->json(['ok' => true, 'task' => $row]);
    }
}
