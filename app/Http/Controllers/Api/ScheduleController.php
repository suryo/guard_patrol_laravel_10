<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TbSchedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    // GET /api/schedules?date=YYYY-MM-DD
    public function index(Request $req)
    {
        $req->validate(['date' => 'required|date_format:Y-m-d']);
        $date = $req->query('date');

        // Jika scheduleDate bertipe DATE/DATETIME, whereDate() juga oke.
        // Untuk aman terhadap tipe VARCHAR(10) 'YYYY-MM-DD', pakai where() biasa.
        $rows = TbSchedule::query()
            ->where('scheduleDate', $date)
            ->orderBy('scheduleStart')
            ->get([
                'uid',
                'scheduleId',
                'mappingId',
                'personId',
                'activityId',
                'checkpointName',
                'scheduleName',
                'scheduleStart',
                'scheduleEnd',
                'scheduleDate',
                'userName',
                'lastUpdated',
            ]);

        // Bentuk respons yang enak dipakai Flutter
        $data = $rows->map(function ($s) {
            return [
                'uid'            => $s->uid,
                'scheduleId'     => $s->scheduleId,
                'mappingId'      => $s->mappingId,
                'personId'       => $s->personId,
                'activityId'     => $s->activityId,
                'checkpointName' => $s->checkpointName,
                'scheduleName'   => $s->scheduleName,
                'scheduleStart'  => $s->scheduleStart, // format sesuai database
                'scheduleEnd'    => $s->scheduleEnd,
                'scheduleDate'   => $s->scheduleDate,  // "YYYY-MM-DD"
                'userName'       => $s->userName,
                'lastUpdated'    => $s->lastUpdated,
            ];
        });

        return response()->json(['data' => $data]);
    }
}
