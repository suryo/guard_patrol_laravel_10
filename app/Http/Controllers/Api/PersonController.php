<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TbPerson;
use App\Models\TbPersonMapping;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    // GET /api/person/by-card/{uid}
    public function byCardUid(string $uid)
    {
        // Normalisasi UID kartu (kalau kamu simpan lowercase, silakan ubah ke strtolower)
        $normUid = strtoupper(trim($uid));

        // 1) Coba: kartu UID langsung disimpan di tb_person.uid
        $person = TbPerson::where('uid', $normUid)->first();

        // 2) Fallback: kartu UID disimpan di tb_person_mapping.uid
        if (!$person) {
            $map = TbPersonMapping::where('uid', $normUid)
                ->when(true, function ($q) {
                    // (Opsional) filter tag yang umum dipakai untuk NFC/kartu
                    // Bila tak perlu, hapus whereIn ini
                    $q->whereIn('mappingTag', ['CARD', 'NFC', 'CARD_TO_PERSON', 'KARTU']);
                })
                ->orderByDesc('lastUpdated')
                ->first();

            if ($map) {
                // a) Jika mappingId numerik, anggap sebagai personId
                if (is_numeric($map->mappingId)) {
                    $person = TbPerson::where('personId', (int)$map->mappingId)->first();
                }

                // b) Jika belum ketemu dan mappingName terisi:
                if (!$person && !empty($map->mappingName)) {
                    // Coba cocokkan ke personName
                    $person = TbPerson::where('personName', $map->mappingName)->first();

                    // Jika belum ketemu, coba cocokkan ke userName
                    if (!$person) {
                        $person = TbPerson::where('userName', $map->mappingName)->first();
                    }
                }
            }
        }

        if (!$person) {
            return response()->json(['message' => 'Card UID not registered'], 404);
        }

        return response()->json([
            'data' => [
                'uid'         => $person->uid,          // bisa sama dengan UID kartu jika kamu simpan di sini
                'personId'    => $person->personId,
                'personName'  => $person->personName,
                'userName'    => $person->userName,
                'isDeleted'   => (bool) $person->isDeleted,
                'lastUpdated' => $person->lastUpdated,
            ]
        ]);
    }
}
