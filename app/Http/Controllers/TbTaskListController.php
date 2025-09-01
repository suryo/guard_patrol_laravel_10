<?php

namespace App\Http\Controllers;
use App\Models\TbTaskList; use Illuminate\Http\Request;

class TbTaskListController extends Controller {
  public function index(){ $items=TbTaskList::orderByDesc('lastUpdated')->paginate(15); return view('task_list.index',compact('items')); }
  public function create(){ return view('task_list.create'); }
  public function store(Request $r){
    $v=$r->validate([
      'taskId'=>'required|max:11','scheduleId'=>'required|max:20','phaseId'=>'nullable|max:20',
      'taskStatus'=>'nullable|in:0,1','userName'=>'nullable|max:60'
    ]);
    TbTaskList::create($v+['lastUpdated'=>now()]); return redirect()->route('task-list.index')->with('ok','Created');
  }
  public function edit(TbTaskList $task_list){ return view('task_list.edit',compact('task_list')); }
  public function update(Request $r, TbTaskList $task_list){
    $v=$r->validate([
      'taskId'=>'required|max:11','scheduleId'=>'required|max:20','phaseId'=>'nullable|max:20',
      'taskStatus'=>'nullable|in:0,1','userName'=>'nullable|max:60'
    ]);
    $task_list->update($v+['lastUpdated'=>now()]); return redirect()->route('task-list.index')->with('ok','Updated');
  }
  public function destroy(TbTaskList $task_list){ $task_list->delete(); return back()->with('ok','Deleted'); }
  public function show(TbTaskList $task_list){ return view('task_list.show',compact('task_list')); }
}
