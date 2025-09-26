<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\TbPatrol;
use App\Models\TbPatrolDetail;
use App\Models\TbCheckpoint;

class PatrolController extends Controller
{
    public function start(Request $r)
    {
        $data = $r->validate([
            'group_uid' => 'required|integer',
            'route_uid' => 'nullable|integer',
        ]);

        $patrol = TbPatrol::create([
            'person_uid' => auth('api')->id(),
            'group_uid'  => $data['group_uid'],
            'route_uid'  => $data['route_uid'] ?? null,
            'started_at' => now('Asia/Jakarta'),
            'status'     => 'ongoing',
        ]);

        return response()->json($patrol, 201);
    }

    public function scan(Request $r, TbPatrol $patrol)
    {
        // pastikan patrol milik user saat ini
        abort_unless($patrol->person_uid === auth('api')->id(), 403);

        $data = $r->validate([
            'checkpoint_uid' => 'required|integer',
            'lat'            => 'nullable|numeric',
            'lng'            => 'nullable|numeric',
            'accuracy'       => 'nullable|numeric',
            'note'           => 'nullable|string|max:500',
            'photo'          => 'nullable|image|max:2048',
            'scan_type'      => 'required|in:qr,nfc,manual',
            'scanned_code'   => 'nullable|string|max:200',
        ]);

        // Validasi QR/NFC terhadap master checkpoint (opsional tapi disarankan)
        $cp = TbCheckpoint::findOrFail($data['checkpoint_uid']);
        if ($data['scan_type'] === 'qr' && $cp->qr_code && $data['scanned_code']) {
            abort_unless(hash_equals($cp->qr_code, $data['scanned_code']), 422, 'QR tidak cocok');
        }
        if ($data['scan_type'] === 'nfc' && $cp->nfc_tag && $data['scanned_code']) {
            abort_unless(hash_equals($cp->nfc_tag, $data['scanned_code']), 422, 'NFC tidak cocok');
        }

        // Validasi geofence (opsional) – radius 75 m misalnya
        if ($cp->latitude && $cp->longitude && $r->filled(['lat','lng'])) {
            $dist = $this->haversine((float)$data['lat'], (float)$data['lng'], (float)$cp->latitude, (float)$cp->longitude);
            abort_if($dist > 75, 422, 'Di luar radius checkpoint ('.$dist.' m)');
        }

        $path = null;
        if ($r->hasFile('photo')) {
            $path = $r->file('photo')->store('patrol', 'public'); // storage/app/public/patrol
        }

        $detail = TbPatrolDetail::create([
            'patrol_uid'     => $patrol->uid,
            'checkpoint_uid' => $cp->uid,
            'scan_type'      => $data['scan_type'],
            'scanned_code'   => $data['scanned_code'] ?? null,
            'lat'            => $data['lat'] ?? null,
            'lng'            => $data['lng'] ?? null,
            'accuracy'       => $data['accuracy'] ?? null,
            'note'           => $data['note'] ?? null,
            'photo'          => $path,
            'scanned_at'     => now('Asia/Jakarta'),
        ]);

        return response()->json([
            'message'   => 'Scan saved',
            'detail'    => $detail,
            'photo_url' => $path ? asset('storage/'.$path) : null,
        ]);
    }

    public function finish(Request $r, TbPatrol $patrol)
    {
        abort_unless($patrol->person_uid === auth('api')->id(), 403);

        $patrol->update([
            'finished_at' => now('Asia/Jakarta'),
            'status'      => 'done',
            'note'        => $r->input('note'),
        ]);

        return response()->json(['message' => 'Patrol finished', 'patrol' => $patrol]);
    }

    public function history(Request $r)
    {
        $from = $r->query('from');
        $to   = $r->query('to');

        $q = TbPatrol::where('person_uid', auth('api')->id())
            ->when($from, fn($w)=>$w->whereDate('started_at','>=',$from))
            ->when($to,   fn($w)=>$w->whereDate('started_at','<=',$to))
            ->orderByDesc('started_at');

        return $q->paginate(10);
    }

    private function haversine($lat1,$lon1,$lat2,$lon2) : float
    {
        $R = 6371000; // meters
        $dLat = deg2rad($lat2-$lat1);
        $dLon = deg2rad($lon2-$lon1);
        $a = sin($dLat/2)**2 + cos(deg2rad($lat1))*cos(deg2rad($lat2))*sin($dLon/2)**2;
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return round($R * $c, 2);
    }
}
