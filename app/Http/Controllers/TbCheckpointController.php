<?php

namespace App\Http\Controllers;

use App\Models\TbCheckpoint;
use Illuminate\Http\Request;

class TbCheckpointController extends Controller
{
    public function index() {
        $items = TbCheckpoint::orderBy('checkpointName')->paginate(15);
        return view('checkpoint.index', compact('items'));
    }
    public function create() { return view('checkpoint.create'); }
    public function store(Request $r) {
        $data = $r->validate([
            'checkpointId'=>'required|max:20|unique:tb_checkpoint,checkpointId',
            'checkpointName'=>'required|max:60',
            'latitude'=>'nullable|numeric',
            'longitude'=>'nullable|numeric',
            'address'=>'nullable|max:255',
            'note'=>'nullable'
        ]);
        TbCheckpoint::create($data + ['lastUpdated'=>now()]);
        return redirect()->route('checkpoint.index')->with('ok','Created');
    }
    public function edit(TbCheckpoint $checkpoint) {
        return view('checkpoint.edit', compact('checkpoint'));
    }
    public function update(Request $r, TbCheckpoint $checkpoint) {
        $data = $r->validate([
            'checkpointId'=>'required|max:20|unique:tb_checkpoint,checkpointId,'.$checkpoint->uid.',uid',
            'checkpointName'=>'required|max:60',
            'latitude'=>'nullable|numeric',
            'longitude'=>'nullable|numeric',
            'address'=>'nullable|max:255',
            'note'=>'nullable'
        ]);
        $checkpoint->update($data + ['lastUpdated'=>now()]);
        return redirect()->route('checkpoint.index')->with('ok','Updated');
    }
    public function destroy(TbCheckpoint $checkpoint) {
        $checkpoint->delete();
        return back()->with('ok','Deleted');
    }
}
