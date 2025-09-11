<?php

namespace App\Http\Controllers;

use App\Models\TbGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TbGroupController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->q ?? '');

        $items = TbGroup::query()
            ->when($q, function ($w) use ($q) {
                $w->where(function ($s) use ($q) {
                    $s->where('groupId', 'like', "%{$q}%")
                      ->orWhere('groupName', 'like', "%{$q}%")
                      ->orWhere('note', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('lastUpdated')
            ->paginate(15)
            ->withQueryString();

        return view('group.index', compact('items'));
    }

    public function create()
    {
        $group = new TbGroup();
        return view('group.create', compact('group'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'groupId'   => ['required','max:40','unique:tb_groups,groupId'],
            'groupName' => ['required','max:100'],
            'timeStart' => ['nullable','date_format:H:i'],
            'timeEnd'   => ['nullable','date_format:H:i'],
            'note'      => ['nullable','max:1000'],
        ]);

        $data['lastUpdated'] = now();

        TbGroup::create($data);

        return redirect()->route('group.index')->with('success', 'Group berhasil ditambahkan.');
    }

    public function show(TbGroup $group)
    {
        return view('group.show', compact('group'));
    }

    public function edit(TbGroup $group)
    {
        return view('group.edit', compact('group'));
    }

    public function update(Request $r, TbGroup $group)
    {
        $data = $r->validate([
            'groupId'   => ['required','max:40', Rule::unique('tb_groups','groupId')->ignore($group->uid, 'uid')],
            'groupName' => ['required','max:100'],
            'timeStart' => ['nullable','date_format:H:i'],
            'timeEnd'   => ['nullable','date_format:H:i'],
            'note'      => ['nullable','max:1000'],
        ]);

        $data['lastUpdated'] = now();

        $group->update($data);

        return redirect()->route('group.index')->with('success', 'Group berhasil diperbarui.');
    }

    public function destroy(TbGroup $group)
    {
        try {
            $group->delete();
            return redirect()->route('group.index')->with('success', 'Group berhasil dihapus.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Tidak dapat menghapus data karena masih terpakai pada relasi.');
        }
    }
}
