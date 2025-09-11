<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\TbSchedule;
use App\Models\TbGroup;
use App\Models\TbScheduleGroup;
use App\Models\TbScheduleTemplate;

class TbScheduleController extends Controller
{
    public function index(Request $r)
    {
        // Query params
        $q    = trim((string) $r->query('q', ''));
        $date = $r->query('d');               // YYYY-MM-DD (tanggal terpilih)
        $m    = $r->query('m');               // YYYY-MM     (bulan kalender)

        // Bulan kalender (fallback kalau format salah)
        try {
            $month = $m ? Carbon::createFromFormat('Y-m', $m)->startOfMonth()
                : Carbon::now()->startOfMonth();
        } catch (\Throwable $e) {
            $month = Carbon::now()->startOfMonth();
        }

        $rangeStart = $month->copy()->startOfMonth()->toDateString();
        $rangeEnd   = $month->copy()->endOfMonth()->toDateString();

        // Penanda kalender (dot di tanggal yang punya schedule)
        $schedules = TbSchedule::query()
            ->when($q, function ($w) use ($q) {
                $w->where(function ($s) use ($q) {
                    $s->where('scheduleId',   'like', "%{$q}%")
                        ->orWhere('personId',   'like', "%{$q}%")
                        ->orWhere('scheduleName', 'like', "%{$q}%"); // checkpointName tidak ada di tabel
                });
            })
            ->whereBetween('scheduleDate', [$rangeStart, $rangeEnd])
            ->get(['uid', 'scheduleId', 'scheduleDate']);

        // Panel kanan: groups yang sudah di-assign untuk $date
        [$groups, $hasGroup] = $this->assignedGroupsForDate($date);

        // Data untuk modal assign
        $allGroups = TbGroup::orderBy('timeStart')->get();

        // Template (paginator supaya ->links() jalan)
        $templates = TbScheduleTemplate::query()
            ->when($r->tq, fn($qq) => $qq->where('templateName', 'like', '%' . $r->tq . '%'))
            ->orderByDesc('lastUpdated')
            ->paginate(10)
            ->appends($r->only(['q', 'd', 'm', 'tq']));

        return view('schedule.index', [
            'q'         => $q,
            'date'      => $date,
            'month'     => $month,
            'schedules' => $schedules,
            'groups'    => $groups,     // hanya jam yang ada
            'hasGroup'  => $hasGroup,   // untuk logic view + auto-open modal
            'templates' => $templates,
            'allGroups' => $allGroups,
        ]);
    }

    private function assignedGroupsForDate(?string $date): array
{
    $buckets = collect();
    if (!$date) return [$buckets, false];

    $schedule = TbSchedule::where('scheduleDate', $date)->first();
    if (!$schedule) return [$buckets, false];

    $pivot = TbScheduleGroup::where('schedule_uid', $schedule->uid)
        ->orderBy('sortOrder')
        ->get();

    if ($pivot->isEmpty()) return [$buckets, false];

    $gmap = TbGroup::whereIn('uid', $pivot->pluck('group_uid'))
        ->get()
        ->keyBy('uid');

    foreach ($pivot as $sg) {
        $hour = (int) substr($sg->timeStart, 0, 2);

        $list = $buckets->get($hour, collect());
        $list->push((object) [
            'sg_uid'    => $sg->uid,                                    // << penting
            'group_uid' => $sg->group_uid,                               // opsional
            'groupName' => optional($gmap->get($sg->group_uid))->groupName ?? 'Group',
            'timeStart' => $sg->timeStart,
            'timeEnd'   => $sg->timeEnd,
        ]);

        $buckets->put($hour, $list);
    }

    $buckets = $buckets->sortKeys();

    $has = $pivot->isNotEmpty(); // cukup ini

    return [$buckets, $has];
}



    /**
     * GET /schedule/assign-group?d=YYYY-MM-DD
     * Kembalikan group yang sudah dipilih untuk tanggal tsb (untuk isi awal modal).
     */
    public function assignGroup(Request $r)
    {
        try {
            $r->validate(['d' => 'required|date_format:Y-m-d']);

            $date = $r->get('d');
            $schedule = TbSchedule::where('scheduleDate', $date)->first();

            if (!$schedule) {
                return response()->json([
                    'schedule_uid' => null,
                    'selected'     => [],
                ]);
            }

            $selected = TbScheduleGroup::where('schedule_uid', $schedule->uid)
                ->pluck('group_uid')->all();

            return response()->json([
                'schedule_uid' => $schedule->uid,
                'selected'     => $selected,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'assignGroup error',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * POST /schedule/assign-group
     * Body: date (Y-m-d), group_uids[]= (array of uid dari tb_groups)
     * Simpan mapping di tb_schedule_group (replace/sync).
     */
    public function saveAssignedGroup(Request $r)
    {
        $data = $r->validate([
            'date'         => 'required|date_format:Y-m-d',
            'group_uids'   => 'array',
            'group_uids.*' => 'integer',
        ]);

        $date      = $data['date'];
        $groupUids = $data['group_uids'] ?? [];

        try {
            DB::transaction(function () use ($date, $groupUids) {

                // cari schedule by date
                $schedule = TbSchedule::where('scheduleDate', $date)->first();

                if (!$schedule) {
                    $schedule = new TbSchedule();
                    $schedule->scheduleDate  = $date;
                    $schedule->scheduleId    = 'SCH-' . $date . '-' . Str::upper(Str::random(4));
                    $schedule->personId      = '00'; // varchar(2) NOT NULL → isi default aman
                    // boleh null juga kalau kolom nullable; di sini kita isi sesuai tanggal:
                    $schedule->scheduleStart = Carbon::parse($date . ' 00:00:00');
                    $schedule->scheduleEnd   = Carbon::parse($date . ' 23:59:00');
                    $schedule->userName      = auth()->user()->name ?? 'system';
                    $schedule->lastUpdated   = now();
                    $schedule->save();
                }

                // reset pivot lama
                TbScheduleGroup::where('schedule_uid', $schedule->uid)->delete();

                // insert pivot baru
                $sort = 1;
                foreach ($groupUids as $gid) {
                    $g = TbGroup::find($gid);
                    if (!$g) continue;

                    TbScheduleGroup::create([
                        'schedule_uid' => $schedule->uid,
                        'group_uid'    => $g->uid,
                        'timeStart'    => $g->timeStart, // simpan dari definisi group
                        'timeEnd'      => $g->timeEnd,
                        'sortOrder'    => $sort++,
                    ]);
                }

                $schedule->update(['lastUpdated' => now()]);
            });

            return response()->json(['ok' => true, 'message' => 'Groups tersimpan untuk tanggal ' . $date]);
        } catch (\Throwable $e) {
            return response()->json([
                'ok'      => false,
                'message' => 'Gagal menyimpan groups',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }



    // --- helper dummy (agar contoh index() tidak error). Ganti dg implementasi Anda.
    private function getTemplatePaginator()
    {
        // return \App\Models\TbScheduleTemplate::orderByDesc('lastUpdated')->paginate(10);
        return collect([]); // placeholder jika tidak diperlukan
    }

    private function buildGroupsByHour(?string $date)
    {
        $buckets = collect(range(0, 23))->mapWithKeys(fn($h) => [$h => collect()]);

        if (!$date) return $buckets;

        $schedule = TbSchedule::where('scheduleDate', $date)->first();
        if (!$schedule) return $buckets;

        $pivot = TbScheduleGroup::where('schedule_uid', $schedule->uid)
            ->orderBy('sortOrder')->get();

        if ($pivot->isEmpty()) return $buckets;

        // ambil nama group
        $gmap = TbGroup::whereIn('uid', $pivot->pluck('group_uid'))->get()->keyBy('uid');

        foreach ($pivot as $sg) {
            $g = $gmap->get($sg->group_uid);
            $hour = (int) substr($sg->timeStart, 0, 2);
            $buckets[$hour] = $buckets[$hour]->push((object)[
                'groupName' => $g?->groupName ?? 'Group',
                'timeStart' => $sg->timeStart,
                'timeEnd'   => $sg->timeEnd,
            ]);
        }

        return $buckets;
    }

    private function getAssignedGroupsByHour(?string $date)
    {
        // return Collection keyed by hour => Collection of rows
        $buckets = collect();                     // default kosong
        if (!$date) return $buckets;

        $schedule = TbSchedule::where('scheduleDate', $date)->first();
        if (!$schedule) return $buckets;

        $pivot = TbScheduleGroup::where('schedule_uid', $schedule->uid)
            ->orderBy('sortOrder')->get();
        if ($pivot->isEmpty()) return $buckets;

        $gmap = TbGroup::whereIn('uid', $pivot->pluck('group_uid'))
            ->get()->keyBy('uid');

        foreach ($pivot as $sg) {
            $hour = (int) substr($sg->timeStart, 0, 2);
            $buckets[$hour] = ($buckets[$hour] ?? collect());
            $buckets[$hour] = $buckets[$hour]->push((object)[
                'groupName' => $gmap[$sg->group_uid]->groupName ?? 'Group',
                'timeStart' => $sg->timeStart,
                'timeEnd'   => $sg->timeEnd,
            ]);
        }

        // urutkan by hour
        return $buckets->sortKeys();
    }
}
