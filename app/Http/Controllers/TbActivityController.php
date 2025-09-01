<?php

namespace App\Http\Controllers;
use App\Models\TbActivity; use Illuminate\Http\Request;

class TbActivityController extends Controller {
  public function index(){ $items=TbActivity::orderByDesc('activityStart')->paginate(15); return view('activity.index',compact('items')); }
  public function create(){ return view('activity.create'); }
  public function store(Request $r){
    $v=$r->validate([
      'activityId'=>'required|max:20|unique:tb_activity,activityId',
      'personId'=>'required|max:2','scheduleId'=>'nullable|max:20',
      'checkpointStart'=>'nullable|max:60','checkpointEnd'=>'nullable|max:60',
      'activityStart'=>'nullable|date','activityEnd'=>'nullable|date|after_or_equal:activityStart',
      'activityStatus'=>'nullable|in:0,1,2'
    ]);
    TbActivity::create($v+['lastUpdated'=>now()]); return redirect()->route('activity.index')->with('ok','Created');
  }
  public function edit(TbActivity $activity){ return view('activity.edit',compact('activity')); }
  public function update(Request $r, TbActivity $activity){
    $v=$r->validate([
      'activityId'=>'required|max:20|unique:tb_activity,activityId,'.$activity->uid.',uid',
      'personId'=>'required|max:2','scheduleId'=>'nullable|max:20',
      'checkpointStart'=>'nullable|max:60','checkpointEnd'=>'nullable|max:60',
      'activityStart'=>'nullable|date','activityEnd'=>'nullable|date|after_or_equal:activityStart',
      'activityStatus'=>'nullable|in:0,1,2'
    ]);
    $activity->update($v+['lastUpdated'=>now()]); return redirect()->route('activity.index')->with('ok','Updated');
  }
  public function destroy(TbActivity $activity){ $activity->delete(); return back()->with('ok','Deleted'); }
  public function show(TbActivity $activity){ return view('activity.show',compact('activity')); }
}
