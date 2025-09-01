<?php

namespace App\Http\Controllers;
use App\Models\TbScheduleTemplate; use Illuminate\Http\Request;

class TbScheduleTemplateController extends Controller {
  public function index(){ $items=TbScheduleTemplate::orderBy('templateName')->paginate(15); return view('schedule_template.index',compact('items')); }
  public function create(){ return view('schedule_template.create'); }
  public function store(Request $r){
    $v=$r->validate([
      'templateId'=>'required|max:11|unique:tb_schedule_template,templateId',
      'templateName'=>'required|max:60',
      'templatePhase'=>'nullable|integer|min:0|max:255',
      'templateMapping'=>'nullable|integer|min:0|max:255',
      'templatePerson'=>'nullable|integer|min:0|max:255',
      'templateStart'=>'nullable|date','templateEnd'=>'nullable|date|after_or_equal:templateStart',
      'templateTask'=>'nullable|string','userName'=>'nullable|max:60'
    ]);
    TbScheduleTemplate::create($v+['lastUpdated'=>now()]); return redirect()->route('schedule-template.index')->with('ok','Created');
  }
  public function edit(TbScheduleTemplate $schedule_template){ return view('schedule_template.edit',compact('schedule_template')); }
  public function update(Request $r, TbScheduleTemplate $schedule_template){
    $v=$r->validate([
      'templateId'=>'required|max:11|unique:tb_schedule_template,templateId,'.$schedule_template->uid.',uid',
      'templateName'=>'required|max:60',
      'templatePhase'=>'nullable|integer|min:0|max:255',
      'templateMapping'=>'nullable|integer|min:0|max:255',
      'templatePerson'=>'nullable|integer|min:0|max:255',
      'templateStart'=>'nullable|date','templateEnd'=>'nullable|date|after_or_equal:templateStart',
      'templateTask'=>'nullable|string','userName'=>'nullable|max:60'
    ]);
    $schedule_template->update($v+['lastUpdated'=>now()]); return redirect()->route('schedule-template.index')->with('ok','Updated');
  }
  public function destroy(TbScheduleTemplate $schedule_template){ $schedule_template->delete(); return back()->with('ok','Deleted'); }
  public function show(TbScheduleTemplate $schedule_template){ return view('schedule_template.show',compact('schedule_template')); }
}
