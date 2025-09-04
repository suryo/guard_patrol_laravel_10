<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\TbReport;

class TbReportController extends Controller
{
    public function index(Request $r)
    {
        $q    = $r->q;
        $date = $r->date;

        $base = TbReport::query()
            ->when($q, function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->where('reportId', 'like', "%{$q}%")
                        ->orWhere('personId', 'like', "%{$q}%")
                        ->orWhere('checkpointName', 'like', "%{$q}%");
                });
            })
            ->when($date, fn($qq) => $qq->whereDate('reportDate', $date));

        // daftar tanggal + hitungan phase per tanggal
        $dates = (clone $base)
            ->selectRaw('reportDate, COUNT(DISTINCT activityId) AS phase_count, COUNT(*) AS total')
            ->groupBy('reportDate')
            ->orderByDesc('reportDate')
            ->paginate(20)
            ->withQueryString();

        // detail laporan utk tanggal yang muncul di halaman ini
        $details = (clone $base)
            ->whereIn('reportDate', $dates->pluck('reportDate'))
            ->orderBy('activityId')
            ->orderBy('reportTime')
            ->get()
            ->groupBy('reportDate')                // -> [date => Collection]
            ->map(fn($g) => $g->groupBy('activityId')); // -> [date => [activityId => rows]]

        return view('report.index', [
            'dates'   => $dates,
            'details' => $details,
            'q'       => $q,
            'date'    => $date,
        ]);
    }
    public function create()
    {
        return view('report.create');
    }
    public function store(Request $r)
    {
        $v = $r->validate([
            'reportId' => 'required|max:20|unique:tb_report,reportId',
            'reportLatitude' => 'nullable|numeric',
            'reportLongitude' => 'nullable|numeric',
            'activityId' => 'nullable|max:20',
            'personId' => 'required|max:2',
            'checkpointName' => 'nullable|max:60',
            'reportNote' => 'nullable|max:255',
            'reportDate' => 'nullable|date',
            'reportTime' => 'nullable|date_format:H:i:s'
        ]);
        TbReport::create($v + ['lastUpdated' => now()]);
        return redirect()->route('report.index')->with('ok', 'Created');
    }
    public function edit(TbReport $report)
    {
        return view('report.edit', compact('report'));
    }
    public function update(Request $r, TbReport $report)
    {
        $v = $r->validate([
            'reportId' => 'required|max:20|unique:tb_report,reportId,' . $report->uid . ',uid',
            'reportLatitude' => 'nullable|numeric',
            'reportLongitude' => 'nullable|numeric',
            'activityId' => 'nullable|max:20',
            'personId' => 'required|max:2',
            'checkpointName' => 'nullable|max:60',
            'reportNote' => 'nullable|max:255',
            'reportDate' => 'nullable|date',
            'reportTime' => 'nullable|date_format:H:i:s'
        ]);
        $report->update($v + ['lastUpdated' => now()]);
        return redirect()->route('report.index')->with('ok', 'Updated');
    }
    public function destroy(TbReport $report)
    {
        $report->delete();
        return back()->with('ok', 'Deleted');
    }
    public function show(TbReport $report)
    {
        return view('report.show', compact('report'));
    }
}
