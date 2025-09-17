<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Models\TbPhase; // pastikan model Phase kamu bernama TbPhase
use App\Models\TbScheduleGroupPhaseActivity;

class SchedulePhaseActivityController extends Controller
{
    /**
     * Simpan banyak activity sekaligus untuk 1 phase.
     * Endpoint: POST /phase/{phase}/activities
     * Payload: { activities: [{task_group_uid, task_group_detail_uid, task_uid?, sortOrder?, activityNote?}, ...] }
     */
    public function store(Request $r, $phase_uid)
{
    // Pastikan phase ada
    $phase = TbPhase::findOrFail($phase_uid);

    // Validasi payload + schedule_group_uid dari frontend
    $data = $r->validate([
        'schedule_group_uid'               => ['required','integer','exists:tb_schedule_group,uid'],
        'activities'                       => ['required','array','min:1'],
        'activities.*.task_group_uid'      => ['required','integer','exists:tb_task_group,uid'],
        'activities.*.task_group_detail_uid'=>['required','integer','exists:tb_task_group_detail,uid'],
        'activities.*.task_uid'            => ['nullable','integer','exists:tb_task,uid'],
        'activities.*.sortOrder'           => ['nullable','integer','min:1'],
        'activities.*.activityNote'        => ['nullable','string'],
    ]);

    $scheduleGroupUid = (int) $data['schedule_group_uid'];

    // Cari uid dari tb_schedule_group_phase yang mengikat (schedule_group_uid, phase_uid)
    $sgPhaseUid = DB::table('tb_schedule_group_phase')
        ->where('schedule_group_uid', $scheduleGroupUid)
        ->where('phase_uid', $phase->uid)
        ->value('uid');

    if (!$sgPhaseUid) {
        return response()->json([
            'ok'      => false,
            'message' => 'Relasi schedule_group_phase tidak ditemukan untuk group & phase ini.',
            'error'   => 'SG_PHASE_NOT_FOUND',
        ], 422);
    }

    // Dedupe by task_group_detail_uid di payload
    $uniq = [];
    $cleanRows = [];
    foreach ($data['activities'] as $row) {
        $key = (int) $row['task_group_detail_uid'];
        if (isset($uniq[$key])) continue;
        $uniq[$key] = true;

        $cleanRows[] = [
            'schedule_group_phase_uid' => (int) $sgPhaseUid,  // dari lookup di atas
            'phase_uid'                => (int) $phase->uid,  // tetap simpan jika tabelmu masih memakainya
            'task_group_uid'           => (int) $row['task_group_uid'],
            'task_group_detail_uid'    => (int) $row['task_group_detail_uid'],
            'task_uid'                 => $row['task_uid'] ?? null,
            'sortOrder'                => $row['sortOrder'] ?? null,
            'activityNote'             => $row['activityNote'] ?? null,
            'userName'                 => auth()->user()->name ?? 'system',
            'created_at'               => now(),
            'updated_at'               => now(),
        ];
    }

    if (!$cleanRows) {
        return response()->json(['ok' => false, 'message' => 'Tidak ada activity yang valid.'], 422);
    }

    try {
        DB::transaction(function () use ($cleanRows) {
            TbScheduleGroupPhaseActivity::insert($cleanRows);
        });

        return response()->json(['ok' => true, 'count' => count($cleanRows)]);
    } catch (QueryException $e) {
        $msg = $e->getMessage();
        // Sesuaikan dengan nama unique index yang kamu pakai (disarankan: uniq_sgphase_detail)
        if (str_contains($msg, 'uniq_sgphase_detail') || str_contains($msg, 'Duplicate entry')) {
            return response()->json([
                'ok'      => false,
                'message' => 'Beberapa detail sudah ada pada schedule group phase ini (duplikat).',
                'error'   => 'DUPLICATE',
            ], 422);
        }
        throw $e;
    }
}


    /**
     * Ambil semua activity milik phase (untuk render ulang UI jika perlu)
     * GET /phase/{phase}/activities
     */
    public function list($phase_uid)
    {
        $phase = TbPhase::with([
            'activities.taskGroup',
            'activities.taskGroupDetail',
            'activities.task',
        ])->findOrFail($phase_uid);

        // bisa kembalikan JSON atau partial view sesuai kebutuhan
        return response()->json([
            'ok' => true,
            'data' => $phase->activities->sortBy('sortOrder')->values(),
        ]);
    }

    /**
     * Hapus satu activity
     * DELETE /phase/activities/{id}
     */
    public function destroy($id)
    {
        $act = TbScheduleGroupPhaseActivity::findOrFail($id);
        $act->delete();

        return response()->json(['ok' => true]);
    }
}
