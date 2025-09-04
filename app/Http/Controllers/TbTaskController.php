<?php

namespace App\Http\Controllers;

use App\Models\TbTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TbTaskController extends Controller
{
public function index(Request $request)
    {
        $q = trim((string) $request->q);

        // KIRI: Task List
        $tasks = TbTask::query()
            ->when($q, function ($w) use ($q) {
                $w->where(function ($s) use ($q) {
                    $s->where('taskId', 'like', "%{$q}%")
                      ->orWhere('taskName', 'like', "%{$q}%")
                      ->orWhere('taskNote', 'like', "%{$q}%")
                      ->orWhere('userName', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('lastUpdated')
            ->paginate(12)
            ->withQueryString();

        // KANAN: Template List (pakai tt.taskName sebagai "template name")
        $templates = DB::table('tb_task_template as tt')
            ->leftJoin('tb_task as t', 't.taskId', '=', 'tt.taskId')
            ->when($q, function ($w) use ($q) {
                $w->where(function ($s) use ($q) {
                    $s->where('tt.taskName', 'like', "%{$q}%")   // nama template
                      ->orWhere('t.taskName', 'like', "%{$q}%")   // nama task
                      ->orWhere('tt.taskId', 'like', "%{$q}%");   // id task
                });
            })
            ->selectRaw('
                tt.taskName as templateName,
                GROUP_CONCAT(
                    COALESCE(t.taskName, tt.taskId)
                    ORDER BY COALESCE(t.taskName, tt.taskId)
                    SEPARATOR "||"
                ) as task_names,
                MAX(tt.lastUpdated) as lastUpdated
            ')
            ->groupBy('tt.taskName')
            ->orderBy('tt.taskName')
            ->get();

        return view('task.index', compact('tasks', 'templates', 'q'));
    }
    public function create()
    {
        return view('task.create');
    }
    public function store(Request $r)
    {
        $v = $r->validate([
            'taskId' => 'required|max:11|unique:tb_task,taskId',
            'taskName' => 'required|max:60',
            'taskNote' => 'nullable|max:120',
            'userName' => 'nullable|max:60'
        ]);
        TbTask::create($v + ['lastUpdated' => now()]);
        return redirect()->route('task.index')->with('ok', 'Created');
    }
    public function edit(TbTask $task)
    {
        return view('task.edit', compact('task'));
    }
    public function update(Request $r, TbTask $task)
    {
        $v = $r->validate([
            'taskId' => 'required|max:11|unique:tb_task,taskId,' . $task->uid . ',uid',
            'taskName' => 'required|max:60',
            'taskNote' => 'nullable|max:120',
            'userName' => 'nullable|max:60'
        ]);
        $task->update($v + ['lastUpdated' => now()]);
        return redirect()->route('task.index')->with('ok', 'Updated');
    }
    public function destroy(TbTask $task)
    {
        $task->delete();
        return back()->with('ok', 'Deleted');
    }
    public function show(TbTask $task)
    {
        return view('task.show', compact('task'));
    }
}
