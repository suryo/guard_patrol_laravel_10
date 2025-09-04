<?php

// app/Http/Controllers/RouteGuardController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TbPerson;
use App\Models\TbPersonMapping;

class TbGuardController extends Controller
{
    // Halaman gabungan Person & Mapping
    public function index(Request $request)
    {
        $qPerson  = $request->qPerson;
        $qMapping = $request->qMapping;

        $people = TbPerson::query()
            ->when($qPerson, function($qr) use ($qPerson) {
                $qr->where('personId', 'like', "%{$qPerson}%")
                   ->orWhere('personName', 'like', "%{$qPerson}%")
                   ->orWhere('userName', 'like', "%{$qPerson}%");
            })
            ->orderBy('personName')
            ->paginate(10, ['*'], 'people_page')
            ->withQueryString();

        $mappings = TbPersonMapping::query()
            ->when($qMapping, function($qr) use ($qMapping) {
                $qr->where('mappingId', 'like', "%{$qMapping}%")
                   ->orWhere('mappingTag', 'like', "%{$qMapping}%")
                   ->orWhere('mappingName', 'like', "%{$qMapping}%")
                   ->orWhere('userName', 'like', "%{$qMapping}%");
            })
            ->orderBy('mappingTag')
            ->paginate(10, ['*'], 'mappings_page')
            ->withQueryString();

        return view('route_guard.index', compact('people','mappings','qPerson','qMapping'));
    }

    // Opsional: detail per person (menampilkan semua mapping sebagai referensi)
    public function show($personId, Request $request)
    {
        $person = TbPerson::where('personId', $personId)->firstOrFail();

        $qMapping = $request->qMapping;
        $mappings = TbPersonMapping::query()
            ->when($qMapping, function($qr) use ($qMapping) {
                $qr->where('mappingId', 'like', "%{$qMapping}%")
                   ->orWhere('mappingTag', 'like', "%{$qMapping}%")
                   ->orWhere('mappingName', 'like', "%{$qMapping}%");
            })
            ->orderBy('mappingTag')
            ->paginate(15)
            ->withQueryString();

        return view('route_guard.show', compact('person','mappings','qMapping'));
    }
}

