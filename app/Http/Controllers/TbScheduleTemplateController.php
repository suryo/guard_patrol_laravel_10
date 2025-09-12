<?php

namespace App\Http\Controllers;

use App\Models\TbScheduleTemplate;
use Illuminate\Http\Request;
use App\Models\TbTaskGroupDetail;

class TbScheduleTemplateController extends Controller
{
    public function index()
    {
        $items = TbScheduleTemplate::orderBy('templateName')->paginate(15);
        return view('schedule_template.index', compact('items'));
    }
    public function create()
    {
        $taskDetails = TbTaskGroupDetail::orderBy('uid')->get(['uid', 'groupName']);
        return view('schedule_template.create', compact('taskDetails'));
    }
    public function store(Request $r)
    {
        $data = $r->validate([
            'templateName'       => ['required', 'string', 'max:150'],
            'personId'           => ['nullable', 'string', 'max:50'],
            'timeStart'          => ['nullable', 'date'],
            'timeEnd'            => ['nullable', 'date'],
            'templateId'         => ['nullable', 'string', 'max:100'],
            // ⬇️ dua sumber pilihan:
            'group_uids'         => ['array'],
            'group_uids.*'       => ['integer'],
            'task_detail_uids'   => ['array'],
            'task_detail_uids.*' => ['integer'],
        ]);

        // Buat template master
        $tpl = \App\Models\TbScheduleTemplate::create([
            'templateId'   => $data['templateId'] ?? null,
            'templateName' => $data['templateName'],
            'personId'     => $data['personId'] ?? null,
            'timeStart'    => $data['timeStart'] ?? null,
            'timeEnd'      => $data['timeEnd'] ?? null,
            'userName'     => auth()->user()->name ?? 'SYSTEM',
            'lastUpdated'  => now(),
        ]);

        // Kumpulkan detail dari dua sumber:
        $detailIds = collect($data['task_detail_uids'] ?? []);

        if (!empty($data['group_uids'])) {
            $byGroups = TbTaskGroupDetail::whereIn('group_uid', $data['group_uids'])
                ->orderBy('group_uid')
                ->orderBy('sortOrder')
                ->pluck('uid');
            $detailIds = $detailIds->concat($byGroups);
        }

        // Hapus duplikat, jaga urutan relatif
        $detailIds = $detailIds->unique()->values();

        // Attach dengan sortOrder berurutan
        if ($detailIds->isNotEmpty()) {
            $attach = [];
            foreach ($detailIds as $i => $uid) {
                $attach[$uid] = ['sortOrder' => $i + 1];
            }
            $tpl->taskDetails()->attach($attach);
        }

        return redirect()->route('schedule-template.index')->with('ok', 'Template dibuat.');
    }
    public function edit(TbScheduleTemplate $schedule_template)
    {
        return view('schedule_template.edit', compact('schedule_template'));
    }
    public function update(Request $r, TbScheduleTemplate $schedule_template)
    {
        $data = $r->validate([
            'templateName'       => ['required', 'string', 'max:150'],
            'personId'           => ['nullable', 'string', 'max:50'],
            'timeStart'          => ['nullable', 'date'],
            'timeEnd'            => ['nullable', 'date'],
            'templateId'         => ['nullable', 'string', 'max:100'],
            // ⬇️ dua sumber pilihan:
            'group_uids'         => ['array'],
            'group_uids.*'       => ['integer'],
            'task_detail_uids'   => ['array'],
            'task_detail_uids.*' => ['integer'],
        ]);

        // Template ID handling (biarkan seperti kode Anda)
        $newId = trim((string)($data['templateId'] ?? ''));
        if ($newId === '') {
            $newId = $schedule_template->templateId ?: \App\Models\TbScheduleTemplate::generateUniqueId();
        } elseif (
            $newId !== $schedule_template->templateId &&
            \App\Models\TbScheduleTemplate::where('templateId', $newId)->exists()
        ) {
            return back()->withErrors(['templateId' => 'Template ID sudah dipakai.'])->withInput();
        }

        $schedule_template->update([
            'templateId'   => $newId,
            'templateName' => $data['templateName'],
            'personId'     => $data['personId'] ?? null,
            'timeStart'    => $data['timeStart'] ?? null,
            'timeEnd'      => $data['timeEnd'] ?? null,
            'userName'     => auth()->user()->name ?? 'SYSTEM',
            'lastUpdated'  => now(),
        ]);

        // Merge detail dari dua sumber
        $detailIds = collect($data['task_detail_uids'] ?? []);
        if (!empty($data['group_uids'])) {
            $byGroups = TbTaskGroupDetail::whereIn('group_uid', $data['group_uids'])
                ->orderBy('group_uid')->orderBy('sortOrder')->pluck('uid');
            $detailIds = $detailIds->concat($byGroups);
        }
        $detailIds = $detailIds->unique()->values();

        $sync = [];
        foreach ($detailIds as $i => $uid) {
            $sync[$uid] = ['sortOrder' => $i + 1];
        }
        $schedule_template->taskDetails()->sync($sync);

        return back()->with('ok', 'Template diperbarui.');
    }

    // ⬇️ Ganti method show agar mendukung modal AJAX
    public function show(Request $r, TbScheduleTemplate $schedule_template)
    {
        if ($r->ajax() || $r->boolean('ajax')) {
            // partial untuk modal
            return view('schedule_template._modal_show', compact('schedule_template'));
        }
        return view('schedule_template.show', compact('schedule_template'));
    }

    // ⬇️ Tambahkan bulk delete
    public function bulkDestroy(Request $r)
    {
        $data = $r->validate([
            'ids'   => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
        ]);

        \App\Models\TbScheduleTemplate::whereIn('uid', $data['ids'])->delete();

        return back()->with('ok', count($data['ids']) . ' template dihapus.');
    }



    // public function destroy(TbScheduleTemplate $schedule_template)
    // {
    //     $schedule_template->delete();
    //     return back()->with('ok', 'Deleted');
    // }
    // public function show(TbScheduleTemplate $schedule_template)
    // {
    //     return view('schedule_template.show', compact('schedule_template'));
    // }
}
