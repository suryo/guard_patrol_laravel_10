<?php

namespace App\Http\Controllers;

use App\Models\TbLogs;
use Illuminate\Http\Request;

class TbLogsController extends Controller
{
    public function index(Request $request)
    {
        $q    = trim($request->q ?? '');
        $user = trim($request->user ?? '');
        $date = $request->date;

        $items = TbLogs::query()
            ->when($q, function ($w) use ($q) {
                $w->where(function ($s) use ($q) {
                    $s->where('activity', 'like', "%{$q}%")
                        ->orWhere('category', 'like', "%{$q}%")
                        ->orWhere('note', 'like', "%{$q}%");
                });
            })
            ->when($user, fn($w) => $w->where('userName', 'like', "%{$user}%"))
            ->when($date, fn($w) => $w->whereDate('lastUpdated', $date))
            ->orderByDesc('lastUpdated')
            ->paginate(15);

        return view('logs.index', compact('items', 'q', 'user', 'date'));
    }

    public function create()
    {
        return view('logs.create');
    }
    public function store(Request $r)
    {
        $v = $r->validate(['activity' => 'nullable|max:30', 'category' => 'nullable|max:30', 'userName' => 'nullable|max:60', 'note' => 'nullable|string']);
        TbLogs::create($v + ['lastUpdated' => now()]);
        return redirect()->route('logs.index')->with('ok', 'Created');
    }
    public function edit(TbLogs $log)
    {
        return view('logs.edit', compact('log'));
    }
    public function update(Request $r, TbLogs $log)
    {
        $v = $r->validate(['activity' => 'nullable|max:30', 'category' => 'nullable|max:30', 'userName' => 'nullable|max:60', 'note' => 'nullable|string']);
        $log->update($v + ['lastUpdated' => now()]);
        return redirect()->route('logs.index')->with('ok', 'Updated');
    }
    public function destroy(TbLogs $log)
    {
        $log->delete();
        return back()->with('ok', 'Deleted');
    }
    public function show(TbLogs $log)
    {
        return view('logs.show', compact('log'));
    }
}
