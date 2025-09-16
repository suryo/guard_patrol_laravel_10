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

        // Validasi payload
        $data = $r->validate([
            'activities' => ['required', 'array', 'min:1'],
            'activities.*.task_group_uid'        => ['required','integer','exists:tb_task_group,uid'],
            'activities.*.task_group_detail_uid' => ['required','integer','exists:tb_task_group_detail,uid'],
            'activities.*.task_uid'              => ['nullable','integer','exists:tb_task,uid'],
            'activities.*.sortOrder'             => ['nullable','integer','min:1'],
            'activities.*.activityNote'          => ['nullable','string'],
        ]);

        // (Opsional) dedupe di sisi server jika ada duplikat task_group_detail_uid di payload yang sama
        $uniq = [];
        $cleanRows = [];
        foreach ($data['activities'] as $row) {
            $key = (int)$row['task_group_detail_uid'];
            if (isset($uniq[$key])) continue; // skip duplikat pada payload yang sama
            $uniq[$key] = true;

            $cleanRows[] = [
                'phase_uid'              => (int)$phase->uid,
                'task_group_uid'         => (int)$row['task_group_uid'],
                'task_group_detail_uid'  => (int)$row['task_group_detail_uid'],
                'task_uid'               => $row['task_uid'] ?? null,
                'sortOrder'              => $row['sortOrder'] ?? null,
                'activityNote'           => $row['activityNote'] ?? null,
                'userName'               => auth()->user()->name ?? 'system',
                'created_at'             => now(),
                'updated_at'             => now(),
            ];
        }

        if (!$cleanRows) {
            return response()->json(['ok' => false, 'message' => 'Tidak ada activity yang valid.'], 422);
        }

        // STRICT MODE: jika ada satupun duplikat (sudah ada di DB), kembalikan 422
        try {
            DB::transaction(function () use ($cleanRows) {
                // insert batch; akan gagal jika melanggar unique index (phase_uid, task_group_detail_uid)
                TbScheduleGroupPhaseActivity::insert($cleanRows);
            });

            return response()->json(['ok' => true, 'count' => count($cleanRows)]);
        } catch (QueryException $e) {
            // Tangkap pelanggaran unique index "uniq_phase_detail"
            // (nama sesuai yang kamu pasang di migration)
            $msg = $e->getMessage();
            if (str_contains($msg, 'uniq_phase_detail') || str_contains($msg, 'Duplicate entry')) {
                return response()->json([
                    'ok'      => false,
                    'message' => 'Beberapa detail sudah ada di phase ini (duplikat).',
                    'error'   => 'DUPLICATE',
                ], 422);
            }
            // lempar ulang jika error lain
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
