<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TbIncident;

class IncidentController extends Controller
{
    public function store(Request $r)
    {
        $data = $r->validate([
            'checkpoint_uid'     => 'required|integer',
            'patrol_detail_uid'  => 'nullable|integer',
            'title'              => 'required|string|max:120',
            'desc'               => 'nullable|string|max:2000',
            'severity'           => 'required|in:low,medium,high',
            'photo'              => 'nullable|image|max:4096',
        ]);

        $path = $r->hasFile('photo') ? $r->file('photo')->store('incident', 'public') : null;

        $inc = TbIncident::create([
            'person_uid'        => auth('api')->id(),
            'checkpoint_uid'    => $data['checkpoint_uid'],
            'patrol_detail_uid' => $data['patrol_detail_uid'] ?? null,
            'title'             => $data['title'],
            'desc'              => $data['desc'] ?? null,
            'severity'          => $data['severity'],
            'photo'             => $path,
            'reported_at'       => now('Asia/Jakarta'),
        ]);

        return response()->json([
            'message' => 'Incident created',
            'item'    => $inc,
            'photo_url' => $path ? asset('storage/'.$path) : null,
        ], 201);
    }

    public function index(Request $r)
    {
        $date = $r->query('date');
        $q = TbIncident::where('person_uid', auth('api')->id())
            ->when($date, fn($w)=>$w->whereDate('reported_at','=',$date))
            ->orderByDesc('reported_at');

        return $q->paginate(10);
    }

    public function show(TbIncident $incident)
    {
        abort_unless($incident->person_uid === auth('api')->id(), 403);
        return $incident;
    }
}
