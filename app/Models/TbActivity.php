<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

class TbActivity extends Model
{
    protected $table = 'tb_activities';
    protected $primaryKey = 'uid';
    public $timestamps = false; // pakai kolom lastUpdated manual

    protected $fillable = [
        'activityId',
        'phase_uid',
        'personId',
        'scheduleId',        // biarkan jika kolom masih ada di DB Anda
        'checkpointStart',
        'checkpointEnd',
        'activityStart',
        'activityEnd',
        'activityStatus',
        'lastUpdated',
    ];

    protected $casts = [
        'activityStart' => 'datetime',
        'activityEnd'   => 'datetime',
        'lastUpdated'   => 'datetime',
    ];

    // =======================
    //        RELASI
    // =======================

    // Activity milik satu Phase
    public function phase()
    {
        return $this->belongsTo(TbPhase::class, 'phase_uid');
    }

    // Many-to-many ke Task via pivot tb_activity_task
    public function tasks()
    {
        return $this->belongsToMany(TbTask::class, 'tb_activity_task', 'activity_uid', 'task_uid')
            ->withPivot(['is_done', 'checked_at', 'notes'])
            ->withTimestamps();
    }

    // (Opsional) relasi ke checkpoint jika Anda punya model TbCheckpoint
    // Catatan: kolom acuan varchar (checkpointId), bukan uid FK.
    public function startCheckpoint()
    {
        return $this->belongsTo(TbCheckpoint::class, 'checkpointStart', 'checkpointId');
    }

    public function endCheckpoint()
    {
        return $this->belongsTo(TbCheckpoint::class, 'checkpointEnd', 'checkpointId');
    }

    // =======================
    //        SCOPES
    // =======================

    public function scopeStatus($q, string $status)
    {
        return $q->where('activityStatus', $status);
    }

    public function scopeBetween($q, Carbon|string $start, Carbon|string $end)
    {
        return $q->where(function ($w) use ($start, $end) {
            $w->whereBetween('activityStart', [$start, $end])
                ->orWhereBetween('activityEnd', [$start, $end]);
        });
    }

    public function scopeOnDate($q, Carbon|string $date)
    {
        // aktivitas yang menyentuh tanggal tsb (start atau end)
        $d0 = Carbon::parse($date)->startOfDay();
        $d1 = Carbon::parse($date)->endOfDay();
        return $this->scopeBetween($q, $d0, $d1);
    }

    // =======================
    //      ACCESSORS
    // =======================

    // Durasi dalam menit (null jika salah satu kosong)
    protected function durationMinutes(): Attribute
    {
        return Attribute::get(function () {
            if (!$this->activityStart || !$this->activityEnd) return null;
            return $this->activityStart->diffInMinutes($this->activityEnd);
        });
    }

    // Semua task di activity ini sudah done?
    protected function isAllTasksDone(): Attribute
    {
        return Attribute::get(function () {
            // hindari N+1: eager load tasks pivot saat query list
            $tasks = $this->relationLoaded('tasks') ? $this->tasks : $this->tasks()->get();
            if ($tasks->isEmpty()) return false;
            return $tasks->every(fn($t) => (bool)$t->pivot->is_done);
        });
    }

    // =======================
    //       HELPERS
    // =======================

    /**
     * Lampirkan tasks ke activity (array of task_uid) tanpa duplikasi.
     */
    public function attachTasks(array $taskUids): void
    {
        $existing = $this->tasks()->pluck('tb_task.uid')->all();
        $toAttach = array_values(array_diff($taskUids, $existing));
        if (!empty($toAttach)) {
            $this->tasks()->attach($toAttach);
        }
    }
}
