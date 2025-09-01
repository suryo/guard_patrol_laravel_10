<?php

namespace App\Http\Controllers;

use App\Models\TbPerson;
use Illuminate\Http\Request;

class TbPersonController extends Controller
{
    public function index() {
        $items = TbPerson::orderBy('personId')->paginate(15);
        return view('person.index', compact('items'));
    }

    public function create() {
        return view('person.create');
    }

    public function store(Request $r) {
        $data = $r->validate([
            'personId'   => 'required|string|max:2|unique:tb_person,personId',
            'personName' => 'required|string|max:60',
            'userName'   => 'nullable|string|max:60',
            'isDeleted'  => 'nullable|in:0,1',
        ]);
        TbPerson::create($data + ['lastUpdated'=>now()]);
        return redirect()->route('person.index')->with('ok','Created');
    }

    public function show(TbPerson $person) {
        return view('person.show', compact('person'));
    }

    public function edit(TbPerson $person) {
        return view('person.edit', compact('person'));
    }

    public function update(Request $r, TbPerson $person) {
        $data = $r->validate([
            'personId'   => 'required|string|max:2|unique:tb_person,personId,'.$person->uid.',uid',
            'personName' => 'required|string|max:60',
            'userName'   => 'nullable|string|max:60',
            'isDeleted'  => 'nullable|in:0,1',
        ]);
        $person->update($data + ['lastUpdated'=>now()]);
        return redirect()->route('person.index')->with('ok','Updated');
    }

    public function destroy(TbPerson $person) {
        $person->delete();
        return back()->with('ok','Deleted');
    }
}
