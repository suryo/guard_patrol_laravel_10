<?php

namespace App\Http\Controllers;
use App\Models\TbTask; use Illuminate\Http\Request;

class TbTaskController extends Controller {
  public function index(){ $items=TbTask::orderBy('taskName')->paginate(15); return view('task.index',compact('items')); }
  public function create(){ return view('task.create'); }
  public function store(Request $r){
    $v=$r->validate([
      'taskId'=>'required|max:11|unique:tb_task,taskId',
      'taskName'=>'required|max:60','taskNote'=>'nullable|max:120','userName'=>'nullable|max:60'
    ]);
    TbTask::create($v+['lastUpdated'=>now()]); return redirect()->route('task.index')->with('ok','Created');
  }
  public function edit(TbTask $task){ return view('task.edit',compact('task')); }
  public function update(Request $r, TbTask $task){
    $v=$r->validate([
      'taskId'=>'required|max:11|unique:tb_task,taskId,'.$task->uid.',uid',
      'taskName'=>'required|max:60','taskNote'=>'nullable|max:120','userName'=>'nullable|max:60'
    ]);
    $task->update($v+['lastUpdated'=>now()]); return redirect()->route('task.index')->with('ok','Updated');
  }
  public function destroy(TbTask $task){ $task->delete(); return back()->with('ok','Deleted'); }
  public function show(TbTask $task){ return view('task.show',compact('task')); }
}
