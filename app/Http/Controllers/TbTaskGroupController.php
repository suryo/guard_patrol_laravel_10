<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TbTask;
use App\Models\TbTaskGroup;
use App\Models\TbTaskGroupDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TbTaskGroupController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->q ?? '');

        $items = TbTaskGroup::query()
            ->when($q, function ($w) use ($q) {
                $w->where('groupId', 'like', "%{$q}%")
                    ->orWhere('groupName', 'like', "%{$q}%")
                    ->orWhere('userName', 'like', "%{$q}%");
            })
            ->orderByDesc('lastUpdated')
            ->paginate(15)
            ->withQueryString();

        return view('task-group.index', compact('items', 'q'));
    }

    public function create()
    {
        return view('task-group.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'groupId'   => ['required', 'max:30', 'unique:tb_task_group,groupId'],
            'groupName' => ['required', 'max:120'],
            'groupNote' => ['nullable', 'string'],
            'userName'  => ['nullable', 'max:60'],
        ]);

        $data['lastUpdated'] = now();
        TbTaskGroup::create($data);

        return redirect()->route('task-group.index')
            ->with('success', 'Task group berhasil dibuat.');
    }

    public function show(TbTaskGroup $task_group)
    {
        // tampilkan detail + count task
        $task_group->loadCount('details')
            ->load(['details.task' => function ($q) {
                $q->select('uid', 'taskId', 'taskName');
            }]);

        return view('task-group.show', compact('task_group'));
    }

    public function edit(TbTaskGroup $task_group)
    {
        return view('task-group.edit', compact('task_group'));
    }

    public function update(Request $request, TbTaskGroup $task_group)
    {
        $data = $request->validate([
            'groupId'   => ['required', 'max:30', Rule::unique('tb_task_group', 'groupId')->ignore($task_group->uid, 'uid')],
            'groupName' => ['required', 'max:120'],
            'groupNote' => ['nullable', 'string'],
            'userName'  => ['nullable', 'max:60'],
        ]);

        $data['lastUpdated'] = now();
        $task_group->update($data);

        return redirect()->route('task-group.index')
            ->with('success', 'Task group berhasil diperbarui.');
    }

    public function destroy(TbTaskGroup $task_group)
    {
        // FK detail onDelete('cascade') akan membersihkan detail
        $task_group->delete();

        return redirect()->route('task-group.index')
            ->with('success', 'Task group berhasil dihapus.');
    }

    public function manage(Request $request)
    {
        return view('task-group.manage');
    }

    public function ajaxTasks(Request $r)
    {
        $q = trim($r->q ?? '');
        $tasks = TbTask::query()
            ->when($q, function ($w) use ($q) {
                $w->where('taskName', 'like', "%{$q}%")
                    ->orWhere('taskId', 'like', "%{$q}%")
                    ->orWhere('userName', 'like', "%{$q}%");
            })
            ->orderByDesc('lastUpdated')
            ->limit(300) // biar enteng di UI; sesuaikan
            ->get(['uid', 'taskId', 'taskName', 'lastUpdated']);

        return response()->json([
            'data' => $tasks->map(fn($t) => [
                'uid' => $t->uid,
                'taskId' => $t->taskId,
                'taskName' => $t->taskName,
                'lastUpdated' => $t->lastUpdated ? $t->lastUpdated : null,
            ]),
        ]);
    }

    // === API: List Group + chips tasks (kanan) ===
    // use App\Models\TbTaskGroup; // sudah ada

public function ajaxGroups(Request $r)
{
    $q = trim($r->q ?? '');

    $groups = TbTaskGroup::query()
        ->when($q, function ($w) use ($q) {
            $w->where('groupName', 'like', "%{$q}%")
              ->orWhere('groupId',   'like', "%{$q}%");
        })
        ->orderBy('groupName')
        ->with([
            'details' => function ($q) {
                $q->orderBy('sortOrder')
                  ->with(['task' => function ($t) {
                      // kolom yang memang ada di tb_task
                      $t->select('uid', 'taskId', 'taskName');
                  }]);
            }
        ])
        ->limit(200)
        // tambahkan lastUpdated karena dipakai di view
        ->get(['uid','groupName','groupId','lastUpdated']);

    // Bentuk payload sesuai yang di-loop oleh JS (json.data)
    $data = $groups->map(function ($g) {
        return [
            'uid'         => $g->uid,
            'groupName'   => $g->groupName,
            'groupId'     => $g->groupId,
            'lastUpdated' => $g->lastUpdated,
            'tasks'       => $g->details->map(function ($d) {
                return [
                    'task_uid'  => $d->task_uid,
                    'taskId'    => $d->task->taskId   ?? null,
                    'taskName'  => $d->task->taskName ?? null,
                    'sortOrder' => $d->sortOrder,
                ];
            })->values(),
        ];
    });

    // <- JS loadGroups() membaca json.data
    return response()->json(['data' => $data]);
}


    // === API: Attach kumpulan task ke 1 group ===
    public function ajaxAttach(Request $r, TbTaskGroup $task_group)
    {
        $taskUids = (array) $r->input('task_uids', []);
        if (empty($taskUids)) {
            return response()->json(['ok' => false, 'message' => 'task_uids kosong'], 422);
        }

        DB::transaction(function () use ($taskUids, $task_group, $r) {
            // Ambil sortOrder max lalu tambahkan
            $last = TbTaskGroupDetail::where('group_uid', $task_group->uid)->max('sortOrder');
            $order = is_null($last) ? 1 : ($last + 1);

            foreach ($taskUids as $uid) {
                // hindari duplikasi pair
                $exists = TbTaskGroupDetail::where('group_uid', $task_group->uid)
                    ->where('task_uid', $uid)->exists();
                if ($exists) continue;

                TbTaskGroupDetail::create([
                    'group_uid'  => $task_group->uid,
                    'task_uid'   => (int) $uid,
                    'sortOrder'  => $order++,
                    'userName'   => $r->user()->name ?? null,
                    'lastUpdated' => now(),
                ]);
            }

            // update lastUpdated group
            $task_group->update(['lastUpdated' => now()]);
        });

        return response()->json(['ok' => true]);
    }

    // === API: Detach 1 task dari group ===
    public function ajaxDetach(TbTaskGroup $task_group, TbTask $task)
    {
        TbTaskGroupDetail::where('group_uid', $task_group->uid)
            ->where('task_uid', $task->uid)
            ->delete();

        $task_group->update(['lastUpdated' => now()]);

        return response()->json(['ok' => true]);
    }
}
