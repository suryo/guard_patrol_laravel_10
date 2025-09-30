<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleHierarchyController extends Controller
{
    /**
     * GET /api/v1/schedule/hierarchy
     * Query:
     *  - schedule_date (Y-m-d) [wajib]
     *  - group_uid (int)       [opsional]
     *  - phase_uid (int)       [opsional]
     */
    public function index(Request $r)
{
    $r->validate([
        'schedule_date' => 'required|date_format:Y-m-d',
        'group_uid'     => 'nullable|integer',
        'phase_uid'     => 'nullable|integer',
    ]);

    $q = DB::table('tb_schedules as schedule')
        ->join('tb_schedule_group as schedule_group', 'schedule_group.schedule_uid', '=', 'schedule.uid')
        ->join('tb_groups as grp', 'schedule_group.group_uid', '=', 'grp.uid') // <— ganti alias
        ->leftJoin('tb_schedule_group_phase as sg_phase', 'schedule_group.uid', '=', 'sg_phase.schedule_group_uid')
        ->leftJoin('tb_phases as phase', 'sg_phase.phase_uid', '=', 'phase.uid')
        ->leftJoin('tb_schedule_group_phase_activity as sgp_activity', 'sgp_activity.schedule_group_phase_uid', '=', 'sg_phase.uid')
        ->leftJoin('tb_task_group as tg', 'sgp_activity.task_group_uid', '=', 'tg.uid')
        ->leftJoin('tb_task as task', 'task.uid', '=', 'sgp_activity.task_uid')
        ->where('schedule.scheduleDate', $r->schedule_date);

    if ($r->filled('group_uid')) {
        $q->where('schedule_group.group_uid', (int)$r->group_uid);
    }
    if ($r->filled('phase_uid')) {
        $q->where('sg_phase.phase_uid', (int)$r->phase_uid);
    }

    $rows = $q->orderBy('grp.groupName')      // <— pakai grp.*
        ->orderBy('phase.phaseId')
        ->orderBy('tg.groupName')
        ->orderBy('sgp_activity.sortOrder')
        ->get([
            'schedule.uid as schedule_uid',
            'schedule.scheduleDate',
            'grp.uid as group_uid',          // <—
            'grp.groupName',                 // <—
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

    // ===== shape JSON seperti sebelumnya =====
    $result = ['scheduleDate' => $r->schedule_date, 'groups' => []];
    $groups = [];
    foreach ($rows as $row) {
        $gKey = (string)($row->group_uid ?? 0);
        if (!isset($groups[$gKey])) {
            $groups[$gKey] = [
                'groupUid'  => $row->group_uid,
                'groupName' => $row->groupName,
                'phases'    => [],
            ];
        }
        $pKey = (string)($row->phase_uid ?? 0);
        if (!isset($groups[$gKey]['phases'][$pKey])) {
            $groups[$gKey]['phases'][$pKey] = [
                'phaseUid'   => $row->phase_uid,
                'phaseId'    => $row->phaseId,
                'phaseDate'  => $row->phaseDate,
                'taskgroups' => [],
            ];
        }
        $tgKey = (string)($row->task_group_uid ?? 0);
        if (!isset($groups[$gKey]['phases'][$pKey]['taskgroups'][$tgKey])) {
            $groups[$gKey]['phases'][$pKey]['taskgroups'][$tgKey] = [
                'taskGroupUid' => $row->task_group_uid,
                'taskgroup'    => $row->taskgroup,
                'tasks'        => [],
            ];
        }
        if (!is_null($row->task_uid)) {
            $groups[$gKey]['phases'][$pKey]['taskgroups'][$tgKey]['tasks'][] = [
                'taskUid'      => $row->task_uid,
                'taskName'     => $row->taskName,
                'sortOrder'    => $row->sortOrder,
                'activityNote' => $row->activityNote,
            ];
        }
    }
    foreach ($groups as &$g) {
        $g['phases'] = array_values($g['phases']);
        foreach ($g['phases'] as &$p) {
            $p['taskgroups'] = array_values($p['taskgroups']);
        }
    }
    $result['groups'] = array_values($groups);

    return response()->json(['ok' => true, 'data' => $result]);
}

}
