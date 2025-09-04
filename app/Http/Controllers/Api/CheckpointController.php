<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckpointController extends Controller
{
    // GET /api/checkpoints?scheduleId=...
    public function index(Request $req)
    {
        $scheduleId = $req->query('scheduleId'); // opsional

        // --- Helper: pilih kolom pertama yang ada di tabel ---
        $cols = Schema::getColumnListing('tb_checkpoint'); // array nama kolom nyata

        $pick = function(array $candidates) use ($cols) {
            foreach ($candidates as $c) {
                if (in_array($c, $cols, true)) return $c;
            }
            return null;
        };

        // Pemetaan kolom → alias API
        $map = [
            'uid'            => $pick(['uid','checkpointUid','uid_cp','id_uid']),
            'checkpointId'   => $pick(['checkpointId','id','cpId','checkpoint_id']),
            'checkpointName' => $pick(['checkpointName','name','checkpoint_name','cpName','title']),
            'lat'            => $pick(['checkLatitude','latitude','lat','cpLat','y']),
            'lng'            => $pick(['checkLongitude','longitude','lng','cpLng','x']),
            'checkStatus'    => $pick(['checkStatus','status','cpStatus']),
            'isDeleted'      => $pick(['isDeleted','deleted','is_deleted']),
            'userName'       => $pick(['userName','updatedBy','createdBy','user_name']),
            'lastUpdated'    => $pick(['lastUpdated','updated_at','created_at']),
        ];

        // Siapkan SELECT dengan alias sebagai nama field API tetap
        $selects = [];
        foreach ($map as $alias => $col) {
            if ($col) {
                $selects[] = DB::raw("`$col` as `$alias`");
            }
        }

        // Jika semua mapping gagal (aneh), fallback select *
        if (empty($selects)) {
            $rows = DB::table('tb_checkpoint')->get();
            return response()->json(['data' => $rows]);
        }

        // Urutkan berdasar nama checkpoint jika ada
        $orderCol = $map['checkpointName'] ?: ($map['checkpointId'] ?: array_values(array_filter($map))[0]);

        // ====== Filter by schedule (opsional) via pivot kalau ada ======
        $hasPivot = Schema::hasTable('tb_schedule_checkpoint')
            && Schema::hasColumn('tb_schedule_checkpoint', 'scheduleId')
            && Schema::hasColumn('tb_schedule_checkpoint', 'checkpointId');

        if ($hasPivot && $scheduleId !== null && $scheduleId !== '') {
            $checkpointIdCol = $map['checkpointId'] ?? 'checkpointId'; // nama nyata di tb_checkpoint
            $rows = DB::table('tb_checkpoint as c')
                ->join('tb_schedule_checkpoint as sc', 'sc.checkpointId', '=', "c.$checkpointIdCol")
                ->where('sc.scheduleId', (int)$scheduleId)
                ->select($selects)
                ->when($map['isDeleted'], fn($q) => $q->where("c.".$map['isDeleted'], 0))
                ->orderBy("c.$orderCol")
                ->get();

            return response()->json(['data' => $rows]);
        }

        // ====== Tanpa pivot / tanpa scheduleId: kembalikan semua checkpoint ======
        $rows = DB::table('tb_checkpoint')
            ->select($selects)
            ->when($map['isDeleted'], fn($q) => $q->where($map['isDeleted'], 0))
            ->orderBy($orderCol)
            ->get();

        return response()->json(['data' => $rows]);
    }
}
