<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TaskController extends Controller
{
    // GET /api/tasks?checkpointId=...&scheduleId=...
    public function index(Request $req)
    {
        // Jadikan opsional agar endpoint tetap mengembalikan data jika pivot belum ada
        $req->validate([
            'checkpointId' => 'nullable|integer',
            'scheduleId'   => 'nullable|integer',
        ]);

        $checkpointId = $req->query('checkpointId');
        $scheduleId   = $req->query('scheduleId');

        // --- deteksi kolom yang ada di tb_task ---
        $taskCols = Schema::getColumnListing('tb_task');

        $pick = function(array $candidates, array $cols) {
            foreach ($candidates as $c) if (in_array($c, $cols, true)) return $c;
            return null;
        };

        // peta kolom nyata -> alias API
        $mapTask = [
            'taskId'     => $pick(['taskId','id','task_id'], $taskCols) ?? 'taskId',
            'taskName'   => $pick(['taskName','name','title'], $taskCols) ?? 'taskName',
            'taskNote'   => $pick(['taskNote','note','desc','description'], $taskCols) ?? 'taskNote',
            'userName'   => $pick(['userName','updatedBy','createdBy','user_name'], $taskCols),
            'lastUpdated'=> $pick(['lastUpdated','updated_at','created_at'], $taskCols),
        ];

        // --- deteksi pivot paling umum ---
        $pivotName = null;
        $pivotCols = [];
        $candidates = [
            'tb_task_list',
            'tb_tasklists',
            'tb_schedule_task',
            'tb_schedule_checkpoint_task',
            'tb_task_mapping',
        ];
        foreach ($candidates as $tbl) {
            if (Schema::hasTable($tbl)) {
                $cols = Schema::getColumnListing($tbl);
                // kita butuh minimal taskId dan salah satu/both scheduleId, checkpointId
                if (in_array('taskId', $cols, true) &&
                    (in_array('checkpointId', $cols, true) || in_array('scheduleId', $cols, true))) {
                    $pivotName = $tbl;
                    $pivotCols = $cols;
                    break;
                }
            }
        }

        // deteksi kolom order dan required di pivot jika ada
        $pivotOrderCol = $pivotName && in_array('order', $pivotCols, true) ? 'order' : ( $pivotName && in_array('seq', $pivotCols, true) ? 'seq' : null );
        $pivotReqCol   = $pivotName && in_array('isRequired', $pivotCols, true) ? 'isRequired' : ( $pivotName && in_array('required', $pivotCols, true) ? 'required' : null );

        // ====== CASE A: ada pivot -> filter dengan checkpointId/scheduleId (kalau dikirim) ======
        if ($pivotName) {
            $q = DB::table("$pivotName as p")
                ->join('tb_task as t', "t.{$mapTask['taskId']}", '=', 'p.taskId');

            if ($checkpointId !== null && $checkpointId !== '' && in_array('checkpointId', $pivotCols, true)) {
                $q->where('p.checkpointId', (int)$checkpointId);
            }
            if ($scheduleId !== null && $scheduleId !== '' && in_array('scheduleId', $pivotCols, true)) {
                $q->where('p.scheduleId', (int)$scheduleId);
            }

            $selects = [
                DB::raw("t.`{$mapTask['taskId']}`   as taskId"),
                DB::raw("t.`{$mapTask['taskName']}` as title"),
                DB::raw("t.`{$mapTask['taskNote']}` as note"),
            ];
            if ($mapTask['userName'])    $selects[] = DB::raw("t.`{$mapTask['userName']}` as userName");
            if ($mapTask['lastUpdated']) $selects[] = DB::raw("t.`{$mapTask['lastUpdated']}` as lastUpdated");
            if ($pivotOrderCol)          $selects[] = DB::raw("p.`$pivotOrderCol` as `order`");
            if ($pivotReqCol)            $selects[] = DB::raw("p.`$pivotReqCol` as `isRequired`");

            // default ordering: order (jika ada) lalu title
            if ($pivotOrderCol) {
                $q->orderBy("p.$pivotOrderCol");
            }
            $q->orderBy("t.{$mapTask['taskName']}");

            $rows = $q->select($selects)->get();
            return response()->json(['data' => $rows]);
        }

        // ====== CASE B: tidak ada pivot -> kembalikan semua task (tanpa filter) ======
        $q = DB::table('tb_task')->select([
            DB::raw("`{$mapTask['taskId']}`   as taskId"),
            DB::raw("`{$mapTask['taskName']}` as title"),
            DB::raw("`{$mapTask['taskNote']}` as note"),
        ]);
        if ($mapTask['userName'])    $q->addSelect(DB::raw("`{$mapTask['userName']}` as userName"));
        if ($mapTask['lastUpdated']) $q->addSelect(DB::raw("`{$mapTask['lastUpdated']}` as lastUpdated"));

        $rows = $q->orderBy($mapTask['taskName'])->get();
        return response()->json(['data' => $rows]);
    }
}
