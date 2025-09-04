<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\TbSchedule;
use App\Models\TbScheduleTemplate;

class TbScheduleController extends Controller
{
    public function index(Request $r)
{
    $q     = trim($r->q ?? '');
    $date  = $r->d ?: Carbon::today()->toDateString();

    // daftar schedule utk tanggal terpilih
    $schedules = TbSchedule::query()
        ->when($q, function ($qq) use ($q) {
            $qq->where(function ($w) use ($q) {
                $w->where('scheduleId','like',"%$q%")
                  ->orWhere('personId','like',"%$q%")
                  ->orWhere('checkpointName','like',"%$q%");
            });
        })
        ->when($date, fn($qq) => $qq->whereDate('scheduleDate',$date))
        ->orderBy('scheduleStart')
        ->get();

    // grup per jam (00..23)
    $groups = collect(range(0,23))->mapWithKeys(function($h) use ($schedules){
        $start = sprintf('%02d:00:00',$h);
        $end   = sprintf('%02d:59:59',$h);
        $items = $schedules->filter(fn($i) => $i->scheduleStart >= $start && $i->scheduleStart <= $end);
        return [ $h => $items->values() ];
    });

    // template schedule (panel bawah kiri)
    $templates = TbScheduleTemplate::orderByDesc('lastUpdated')->paginate(10);

    // untuk kalender (bulan yang sedang dilihat)
    $month = $r->m ? Carbon::createFromFormat('Y-m', $r->m) : Carbon::parse($date)->startOfMonth();

    return view('schedule.index', compact('q','date','schedules','groups','templates','month'));
}
    public function create()
    {
        return view('schedule.create');
    }
    public function store(Request $r)
    {
        $v = $r->validate([
            'scheduleId' => 'required|max:20|unique:tb_schedule,scheduleId',
            'mappingId' => 'nullable|max:5',
            'personId' => 'required|max:2',
            'activityId' => 'nullable|max:20',
            'checkpointName' => 'nullable|max:60',
            'scheduleName' => 'nullable|max:60',
            'scheduleStart' => 'nullable|date',
            'scheduleEnd' => 'nullable|date|after_or_equal:scheduleStart',
            'scheduleDate' => 'nullable|date',
            'userName' => 'nullable|max:60'
        ]);
        TbSchedule::create($v + ['lastUpdated' => now()]);
        return redirect()->route('schedule.index')->with('ok', 'Created');
    }
    public function edit(TbSchedule $schedule)
    {
        return view('schedule.edit', compact('schedule'));
    }
    public function update(Request $r, TbSchedule $schedule)
    {
        $v = $r->validate([
            'scheduleId' => 'required|max:20|unique:tb_schedule,scheduleId,' . $schedule->uid . ',uid',
            'mappingId' => 'nullable|max:5',
            'personId' => 'required|max:2',
            'activityId' => 'nullable|max:20',
            'checkpointName' => 'nullable|max:60',
            'scheduleName' => 'nullable|max:60',
            'scheduleStart' => 'nullable|date',
            'scheduleEnd' => 'nullable|date|after_or_equal:scheduleStart',
            'scheduleDate' => 'nullable|date',
            'userName' => 'nullable|max:60'
        ]);
        $schedule->update($v + ['lastUpdated' => now()]);
        return redirect()->route('schedule.index')->with('ok', 'Updated');
    }
    public function destroy(TbSchedule $schedule)
    {
        $schedule->delete();
        return back()->with('ok', 'Deleted');
    }
    public function show(TbSchedule $schedule)
    {
        return view('schedule.show', compact('schedule'));
    }
}
