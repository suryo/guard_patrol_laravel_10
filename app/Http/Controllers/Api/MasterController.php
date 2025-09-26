<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\TbGroup;
use App\Models\TbCheckpoint;
// tambahkan model jadwalmu kalau ada, mis. TbSchedule, TbScheduleGroup

class MasterController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api'); // BUKAN 'auth'
    }
    public function mySchedule(Request $r)
    {
        $date = $r->query('date') ?: now('Asia/Jakarta')->toDateString();
        // TODO: sesuaikan query jadwalmu; di bawah dummy format respons
        return response()->json([
            'date' => $date,
            'groups' => TbGroup::orderBy('timeStart')->take(5)->get(),
        ]);
    }

    public function checkpoints(Request $r)
    {
        $checkpointid = (int) $r->query('checkpointid');
        $q = TbCheckpoint::query()
            ->when($checkpointid, fn($w) => $w->where('checkpointId', $checkpointid))
            ->orderBy('checkpointId');

        return response()->json([
            'items' => $q->get(['uid', 'checkpointId', 'checkpointName', 'latitude as lat', 'longitude as lng']),
        ]);
    }

    public function routes(Request $r)
    {
        // optional, jika kamu punya tb_route
        return response()->json(['items' => []]);
    }
}
