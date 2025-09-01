<?php

namespace App\Http\Controllers;
use App\Models\TbTaskTemplate; use Illuminate\Http\Request;

class TbTaskTemplateController extends Controller {
  public function index(){ $items=TbTaskTemplate::orderBy('taskName')->paginate(15); return view('task_template.index',compact('items')); }
  public function create(){ return view('task_template.create'); }
  public function store(Request $r){
    $v=$r->validate([
      'taskId'=>'required|max:11','taskName'=>'required|max:60','taskNote'=>'nullable|max:120'
    ]);
    TbTaskTemplate::create($v+['lastUpdated'=>now()]); return redirect()->route('task-template.index')->with('ok','Created');
  }
  public function edit(TbTaskTemplate $task_template){ return view('task_template.edit',compact('task_template')); }
  public function update(Request $r, TbTaskTemplate $task_template){
    $v=$r->validate([
      'taskId'=>'required|max:11','taskName'=>'required|max:60','taskNote'=>'nullable|max:120'
    ]);
    $task_template->update($v+['lastUpdated'=>now()]); return redirect()->route('task-template.index')->with('ok','Updated');
  }
  public function destroy(TbTaskTemplate $task_template){ $task_template->delete(); return back()->with('ok','Deleted'); }
  public function show(TbTaskTemplate $task_template){ return view('task_template.show',compact('task_template')); }
}
