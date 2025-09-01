<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; use Illuminate\Support\Facades\DB;

class LaporanController extends Controller {
  public function index(Request $r){
    $q = DB::table('laporan');
    if($r->filled('personName'))   $q->where('personName','like','%'.$r->personName.'%');
    if($r->filled('scheduleDate')) $q->whereDate('scheduleDate',$r->scheduleDate);
    if($r->filled('reportDate'))   $q->whereDate('reportDate',$r->reportDate);
    $items = $q->orderByDesc('reportDate')->paginate(20)->withQueryString();
    return view('laporan.index', compact('items'));
  }
}
