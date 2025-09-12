<?php

namespace App\Http\Controllers;

use App\Models\TbTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TbTaskController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->q);

        $query = TbTask::query()
            ->when($q, function ($w) use ($q) {
                $w->where(function ($s) use ($q) {
                    $s->where('taskId', 'like', "%{$q}%")
                      ->orWhere('taskName', 'like', "%{$q}%")
                      ->orWhere('taskNote', 'like', "%{$q}%")
                      ->orWhere('userName', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('lastUpdated');

        // Jika diminta JSON (untuk kebutuhan AJAX), kembalikan list sederhana
        if ($request->expectsJson()) {
            $tasks = $query->limit(300)->get(['uid','taskId','taskName','taskNote','userName','lastUpdated']);
            return response()->json(['data' => $tasks]);
        }

        // View biasa
        $tasks = $query->paginate(12)->withQueryString();

        // Sisi kanan (template ringkas seperti sebelumnya)
        $templates = DB::table('tb_task_template as tt')
            ->leftJoin('tb_task as t', 't.taskId', '=', 'tt.taskId')
            ->when($q, function ($w) use ($q) {
                $w->where(function ($s) use ($q) {
                    $s->where('tt.taskName', 'like', "%{$q}%")
                      ->orWhere('t.taskName', 'like', "%{$q}%")
                      ->orWhere('tt.taskId', 'like', "%{$q}%");
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
        $data = $this->validatePayload($r);

        $task = TbTask::create($data + ['lastUpdated' => now()]);

        if ($r->expectsJson()) {
            return response()->json(['ok' => true, 'item' => $task], 201);
        }
        return redirect()->route('task.index')->with('ok', 'Created');
    }

    public function show(TbTask $task)
    {
        return view('task.show', compact('task'));
    }

    public function edit(TbTask $task)
    {
        return view('task.edit', compact('task'));
    }

    public function update(Request $r, TbTask $task)
    {
        $data = $this->validatePayload($r, $task->uid);

        $task->update($data + ['lastUpdated' => now()]);

        if ($r->expectsJson()) {
            return response()->json(['ok' => true, 'item' => $task]);
        }
        return redirect()->route('task.index')->with('ok', 'Updated');
    }

    public function destroy(Request $r, TbTask $task)
    {
        $task->delete();

        if ($r->expectsJson()) {
            return response()->json(['ok' => true]);
        }
        return back()->with('ok', 'Deleted');
    }

    // Endpoint ringan JSON (opsional) → bisa dipakai untuk /ajax/tasks
    public function listJson(Request $r)
    {
        $q = trim((string) $r->q);
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
            ->limit(300)
            ->get(['uid','taskId','taskName','lastUpdated']);

        return response()->json(['data' => $tasks]);
    }

    private function validatePayload(Request $r, ?int $ignoreUid = null): array
    {
        $unique = Rule::unique('tb_task', 'taskId');
        if ($ignoreUid) {
            $unique = $unique->ignore($ignoreUid, 'uid');
        }

        $validated = $r->validate([
            'taskId'   => ['required','string','max:30', $unique],
            'taskName' => ['required','string','max:180'],
            'taskNote' => ['nullable','string','max:255'],
            'userName' => ['nullable','string','max:60'],
        ]);

        // Trim semua string
        foreach ($validated as $k => $v) {
            if (is_string($v)) $validated[$k] = trim($v);
        }
        return $validated;
    }
}
