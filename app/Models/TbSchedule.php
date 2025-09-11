<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbSchedule extends Model
{
    protected $table = 'tb_schedules';
    protected $primaryKey = 'uid';
    public $timestamps = false; // pakai lastUpdated

    protected $fillable = [
        'scheduleId',
        'scheduleName',
        'scheduleDate',
        'personId',
        'scheduleStart',   // DATETIME
        'scheduleEnd',     // DATETIME
        'userName',
        'lastUpdated',
    ];

    // === RELASI ===
    public function groups()
    {
        return $this->belongsToMany(TbGroup::class, 'tb_schedule_group', 'schedule_uid', 'group_uid')
            // ->withTimestamps() // HAPUS kalau tb_schedule_group tidak punya timestamps
            ->withPivot(['timeStart', 'timeEnd', 'sortOrder']);
    }

    public function scheduleGroups()
    {
        return $this->hasMany(TbScheduleGroup::class, 'schedule_uid');
    }

    public function phases()
    {
        return $this->hasManyThrough(
            TbPhase::class,
            TbScheduleGroup::class,
            'schedule_uid',
            'schedule_group_uid',
            'uid',
            'uid'
        );
    }

    public function activities()
    {
        return $this->hasManyThrough(
            TbActivity::class,
            TbPhase::class,
            'schedule_group_uid',
            'phase_uid',
            'uid',
            'uid'
        );
    }
}
