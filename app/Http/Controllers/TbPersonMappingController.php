<?php

namespace App\Http\Controllers;
use App\Models\TbPersonMapping; use Illuminate\Http\Request;

class TbPersonMappingController extends Controller {
  public function index(){ $items=TbPersonMapping::orderBy('mappingName')->paginate(15); return view('person_mapping.index',compact('items')); }
  public function create(){ return view('person_mapping.create'); }
  public function store(Request $r){
    $v=$r->validate([
      'mappingId'=>'required|max:5|unique:tb_person_mapping,mappingId',
      'mappingTag'=>'nullable|max:20','mappingName'=>'required|max:60','userName'=>'nullable|max:60'
    ]);
    TbPersonMapping::create($v+['lastUpdated'=>now()]); return redirect()->route('person-mapping.index')->with('ok','Created');
  }
  public function edit(TbPersonMapping $person_mapping){ return view('person_mapping.edit',compact('person_mapping')); }
  public function update(Request $r, TbPersonMapping $person_mapping){
    $v=$r->validate([
      'mappingId'=>'required|max:5|unique:tb_person_mapping,mappingId,'.$person_mapping->uid.',uid',
      'mappingTag'=>'nullable|max:20','mappingName'=>'required|max:60','userName'=>'nullable|max:60'
    ]);
    $person_mapping->update($v+['lastUpdated'=>now()]); return redirect()->route('person-mapping.index')->with('ok','Updated');
  }
  public function destroy(TbPersonMapping $person_mapping){ $person_mapping->delete(); return back()->with('ok','Deleted'); }
  public function show(TbPersonMapping $person_mapping){ return view('person_mapping.show',compact('person_mapping')); }
}
