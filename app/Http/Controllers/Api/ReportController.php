<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TbReport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    // POST /api/report
    // Contoh body JSON:
    // {
    //   "activityId": "ACT-20250903-0001",
    //   "personId": "05",
    //   "checkpointName": "Lobby",
    //   "reportNote": "Semua aman",
    //   "reportLatitude": -7.3001,
    //   "reportLongitude": 112.7001,
    //   "reportDate": "2025-09-03",       // opsional (default: today)
    //   "reportTime": "14:32:00"          // opsional (default: now)
    // }
    public function store(Request $req)
    {
        $data = $req->validate([
            'reportId'       => 'nullable|string|max:20',   // <= 20 biar aman
            'activityId'     => 'required|string|max:40',
            'personId'       => 'required|string|max:20',
            'checkpointName' => 'required|string|max:100',
            'reportNote'     => 'nullable|string',
            'reportLatitude' => 'nullable',
            'reportLongitude' => 'nullable',
            'reportDate'     => 'nullable|date_format:Y-m-d',
            'reportTime'     => 'nullable|date_format:H:i:s',
        ]);

        $now = \Illuminate\Support\Carbon::now();

        // Generator ID ≤ 20 char, contoh: R25090403252142 (1 + 12 + 2 = 15)
        // Format: 'R' + yymmddHHMMSS + 2-digit random
        $genId = 'R' . $now->format('ymdHis') . str_pad((string)random_int(0, 99), 2, '0', STR_PAD_LEFT);

        // Pakai reportId dari request kalau ada, tapi tetap potong ke max 20
        $reportId = isset($data['reportId']) ? mb_substr($data['reportId'], 0, 20) : $genId;

        // Default tanggal & jam kalau tidak dikirim
        $date = $data['reportDate'] ?? $now->format('Y-m-d');
        $time = $data['reportTime'] ?? $now->format('H:i:s');

        // Normalisasi koordinat: ubah koma menjadi titik
        $lat = isset($data['reportLatitude'])  ? (string)str_replace(',', '.', (string)$data['reportLatitude'])   : null;
        $lng = isset($data['reportLongitude']) ? (string)str_replace(',', '.', (string)$data['reportLongitude'])  : null;

        $report = \App\Models\TbReport::create([
            'reportId'       => $reportId,
            'activityId'     => $data['activityId'],
            'personId'       => $data['personId'],
            'checkpointName' => $data['checkpointName'],
            'reportNote'     => $data['reportNote'] ?? null,
            'reportLatitude' => $lat,
            'reportLongitude' => $lng,
            'reportDate'     => $date,
            'reportTime'     => $time,
            'lastUpdated'    => $now->format('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'message' => 'Report submitted',
            'data'    => $report
        ], 201);
    }
}
