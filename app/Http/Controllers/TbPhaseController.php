<?php

namespace App\Http\Controllers;
use App\Models\TbPhase; use Illuminate\Http\Request;

class TbPhaseController extends Controller {
  public function index(){ $items=TbPhase::orderByDesc('phaseDate')->paginate(15); return view('phase.index',compact('items')); }
  public function create(){ return view('phase.create'); }
  public function store(Request $r){
    $v=$r->validate(['phaseId'=>'required|max:20|unique:tb_phase,phaseId','phaseDate'=>'nullable|date']);
    TbPhase::create($v+['lastUpdated'=>now()]); return redirect()->route('phase.index')->with('ok','Created');
  }
  public function edit(TbPhase $phase){ return view('phase.edit',compact('phase')); }
  public function update(Request $r, TbPhase $phase){
    $v=$r->validate(['phaseId'=>'required|max:20|unique:tb_phase,phaseId,'.$phase->uid.',uid','phaseDate'=>'nullable|date']);
    $phase->update($v+['lastUpdated'=>now()]); return redirect()->route('phase.index')->with('ok','Updated');
  }
  public function destroy(TbPhase $phase){ $phase->delete(); return back()->with('ok','Deleted'); }
  public function show(TbPhase $phase){ return view('phase.show',compact('phase')); }
}
