<?php

namespace App\Http\Controllers;
use App\Models\TbSchedule; use Illuminate\Http\Request;

class TbScheduleController extends Controller {
  public function index(){ $items=TbSchedule::orderByDesc('scheduleDate')->paginate(15); return view('schedule.index',compact('items')); }
  public function create(){ return view('schedule.create'); }
  public function store(Request $r){
    $v=$r->validate([
      'scheduleId'=>'required|max:20|unique:tb_schedule,scheduleId',
      'mappingId'=>'nullable|max:5','personId'=>'required|max:2',
      'activityId'=>'nullable|max:20','checkpointName'=>'nullable|max:60',
      'scheduleName'=>'nullable|max:60','scheduleStart'=>'nullable|date',
      'scheduleEnd'=>'nullable|date|after_or_equal:scheduleStart','scheduleDate'=>'nullable|date',
      'userName'=>'nullable|max:60'
    ]);
    TbSchedule::create($v+['lastUpdated'=>now()]); return redirect()->route('schedule.index')->with('ok','Created');
  }
  public function edit(TbSchedule $schedule){ return view('schedule.edit',compact('schedule')); }
  public function update(Request $r, TbSchedule $schedule){
    $v=$r->validate([
      'scheduleId'=>'required|max:20|unique:tb_schedule,scheduleId,'.$schedule->uid.',uid',
      'mappingId'=>'nullable|max:5','personId'=>'required|max:2',
      'activityId'=>'nullable|max:20','checkpointName'=>'nullable|max:60',
      'scheduleName'=>'nullable|max:60','scheduleStart'=>'nullable|date',
      'scheduleEnd'=>'nullable|date|after_or_equal:scheduleStart','scheduleDate'=>'nullable|date',
      'userName'=>'nullable|max:60'
    ]);
    $schedule->update($v+['lastUpdated'=>now()]); return redirect()->route('schedule.index')->with('ok','Updated');
  }
  public function destroy(TbSchedule $schedule){ $schedule->delete(); return back()->with('ok','Deleted'); }
  public function show(TbSchedule $schedule){ return view('schedule.show',compact('schedule')); }
}
