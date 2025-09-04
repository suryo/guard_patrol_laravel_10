<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TbActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ActivityController extends Controller
{
    // POST /api/activity/start
    public function start(Request $req)
    {
        $data = $req->validate([
            'activityId'      => 'nullable|string|max:20',
            'personId'        => 'required|string|max:2',
            'scheduleId'      => 'required|string|max:20',
            'checkpointStart' => 'required|string|max:60',
            'activityStart'   => 'nullable|date_format:Y-m-d H:i:s',
        ]);

        $now = Carbon::now()->format('Y-m-d H:i:s');

        $act = TbActivity::create([
            'activityId'      => $data['activityId'] ?? uniqid('ACT-'),
            'personId'        => $data['personId'],
            'scheduleId'      => $data['scheduleId'],
            'checkpointStart' => $data['checkpointStart'],
            'checkpointEnd'   => null,
            'activityStart'   => $data['activityStart'] ?? $now,
            'activityEnd'     => null,
            'activityStatus'  => '0',   // 0 = belum selesai
            'lastUpdated'     => $now,
        ]);

        return response()->json([
            'message' => 'Activity started',
            'data'    => $act
        ], 201);
    }

    // POST /api/activity/end
    public function end(Request $req)
    {
        $data = $req->validate([
            'activityId'    => 'required|string|max:20',
            'checkpointEnd' => 'nullable|string|max:60',
            'activityEnd'   => 'nullable|date_format:Y-m-d H:i:s',
        ]);

        $act = TbActivity::find($data['activityId']);
        if (!$act) {
            return response()->json(['message' => 'Activity not found'], 404);
        }

        $now = Carbon::now()->format('Y-m-d H:i:s');

        $act->checkpointEnd = $data['checkpointEnd'] ?? null;
        $act->activityEnd   = $data['activityEnd'] ?? $now;
        $act->activityStatus = '1';  // 1 = selesai/done
        $act->lastUpdated    = $now;

        $act->save();

        return response()->json([
            'message' => 'Activity ended',
            'data'    => $act
        ]);
    }
}
