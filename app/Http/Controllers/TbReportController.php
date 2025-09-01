<?php

namespace App\Http\Controllers;
use App\Models\TbReport; use Illuminate\Http\Request;

class TbReportController extends Controller {
  public function index(){ $items=TbReport::orderByDesc('reportDate')->orderByDesc('reportTime')->paginate(15); return view('report.index',compact('items')); }
  public function create(){ return view('report.create'); }
  public function store(Request $r){
    $v=$r->validate([
      'reportId'=>'required|max:20|unique:tb_report,reportId',
      'reportLatitude'=>'nullable|numeric','reportLongitude'=>'nullable|numeric',
      'activityId'=>'nullable|max:20','personId'=>'required|max:2',
      'checkpointName'=>'nullable|max:60','reportNote'=>'nullable|max:255',
      'reportDate'=>'nullable|date','reportTime'=>'nullable|date_format:H:i:s'
    ]);
    TbReport::create($v+['lastUpdated'=>now()]); return redirect()->route('report.index')->with('ok','Created');
  }
  public function edit(TbReport $report){ return view('report.edit',compact('report')); }
  public function update(Request $r, TbReport $report){
    $v=$r->validate([
      'reportId'=>'required|max:20|unique:tb_report,reportId,'.$report->uid.',uid',
      'reportLatitude'=>'nullable|numeric','reportLongitude'=>'nullable|numeric',
      'activityId'=>'nullable|max:20','personId'=>'required|max:2',
      'checkpointName'=>'nullable|max:60','reportNote'=>'nullable|max:255',
      'reportDate'=>'nullable|date','reportTime'=>'nullable|date_format:H:i:s'
    ]);
    $report->update($v+['lastUpdated'=>now()]); return redirect()->route('report.index')->with('ok','Updated');
  }
  public function destroy(TbReport $report){ $report->delete(); return back()->with('ok','Deleted'); }
  public function show(TbReport $report){ return view('report.show',compact('report')); }
}
