<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\TbPhase;                 // master phase
use App\Models\TbScheduleGroup;         // group waktu (pivot schedule <-> group)
use App\Models\TbScheduleGroupPhase;    // pivot: schedule_group <-> phase (per tanggal)
use Illuminate\Http\Request;

class PhaseAjaxController extends Controller
{
    /**
     * List phase yang SUDAH di-assign ke satu schedule_group pada tanggal d=YYYY-MM-DD
     * GET /ajax/schedule-group/{uid}/phases?d=2025-09-11
     */
    public function index($uid, Request $r)
    {
        $date  = $r->query('d');
        $group = TbScheduleGroup::findOrFail($uid);

        $links = TbScheduleGroupPhase::with('phase')
            ->where('schedule_group_uid', $group->uid)
            ->when($date, fn($q) => $q->whereDate('phaseDate', $date))
            ->orderBy('sortOrder')
            ->get();

        $items = $links->map(fn($l) => [
            'link_uid'   => $l->uid,
            'phase_uid'  => $l->phase_uid,
            'phaseName'  => $l->phase?->phaseName ?? '(phase)',
            'phaseId'    => $l->phase?->phaseId,
            'phaseOrder' => $l->phase?->phaseOrder,
            'phaseDate'  => $l->phaseDate,
        ]);

        return response()->json([
            'ok'     => true,
            'group'  => ['uid' => $group->uid, 'timeStart' => $group->timeStart, 'timeEnd' => $group->timeEnd],
            'phases' => $items,
        ]);
    }

    /**
     * Options untuk modal (daftar phase master dari tb_phase)
     * GET /ajax/phases/options
     */
    public function options()
    {
        try {
            $opts = TbPhase::orderBy('phaseOrder')
                ->orderBy('phaseName')
                ->get(['uid', 'phaseId', 'phaseName', 'phaseOrder']);

            return response()->json(['ok' => true, 'options' => $opts]);
        } catch (\Throwable $e) {
            \Log::error('phases.options error: ' . $e->getMessage());
            return response()->json(['ok' => false, 'options' => []], 200);
        }
    }

    /**
     * Assign phase master ke schedule_group pada tanggal tertentu (buat link pivot)
     * POST /ajax/schedule-group/{uid}/phases
     * body: { phase_uid:int, phaseDate:date }
     */
    public function store($uid, Request $r)
    {
        $data = $r->validate([
            'phase_uid' => ['required', 'integer', 'exists:tb_phases,uid'],
            'phaseDate' => ['required', 'date'],
        ]);

        $group = TbScheduleGroup::findOrFail($uid);

        $nextOrder = (int) TbScheduleGroupPhase::where('schedule_group_uid', $group->uid)
            ->whereDate('phaseDate', $data['phaseDate'])
            ->max('sortOrder');
        $nextOrder = $nextOrder ? $nextOrder + 1 : 1;

        // hindari duplikat (unique di migration juga disarankan)
        $existing = TbScheduleGroupPhase::where([
            'schedule_group_uid' => $group->uid,
            'phase_uid'          => $data['phase_uid'],
            'phaseDate'          => $data['phaseDate'],
        ])->first();

        if ($existing) {
            return response()->json(['ok' => true, 'link_uid' => $existing->uid, 'dup' => true], 200);
        }

        $link = TbScheduleGroupPhase::create([
            'schedule_group_uid' => $group->uid,
            'phase_uid'          => $data['phase_uid'],
            'phaseDate'          => $data['phaseDate'],
            'sortOrder'          => $nextOrder,
        ]);

        return response()->json(['ok' => true, 'link_uid' => $link->uid], 201);
    }

    /**
     * Edit assignment (ganti phase / tanggal / urutan)
     * PUT /ajax/schedule-group-phase/{link}
     * body: { phase_uid:int, phaseDate:date, sortOrder?:int }
     */
    public function update(TbScheduleGroupPhase $link, Request $r)
    {
        $data = $r->validate([
            'phase_uid' => ['required', 'integer', 'exists:tb_phases,uid'],
            'phaseDate' => ['required', 'date'],
            'sortOrder' => ['nullable', 'integer', 'min:1'],
        ]);

        // Cek duplikat ketika ubah
        $dup = TbScheduleGroupPhase::where('schedule_group_uid', $link->schedule_group_uid)
            ->where('phase_uid', $data['phase_uid'])
            ->whereDate('phaseDate', $data['phaseDate'])
            ->where('uid', '!=', $link->uid)
            ->exists();

        if ($dup) {
            return response()->json(['ok' => false, 'message' => 'Phase sudah ditautkan pada tanggal tsb.'], 422);
        }

        $link->update(array_filter($data, fn($v) => $v !== null));
        return response()->json(['ok' => true]);
    }

    /**
     * Hapus assignment (bukan hapus master phase)
     * DELETE /ajax/schedule-group-phase/{link}
     */
    public function destroy(TbScheduleGroupPhase $link)
    {
        $link->delete();
        return response()->json(['ok' => true]);
    }
}
