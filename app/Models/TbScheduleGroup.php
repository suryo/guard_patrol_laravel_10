<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbScheduleGroup extends Model
{
    protected $table = 'tb_schedule_group';
    protected $primaryKey = 'uid';
    public $timestamps = true; // ada created_at & updated_at di migration

    protected $fillable = [
        'schedule_uid',
        'group_uid',
        'timeStart',
        'timeEnd',
        'sortOrder',
    ];

    // === RELASI ===

    // pivot ini milik satu schedule
    public function schedule()
    {
        return $this->belongsTo(TbSchedule::class, 'schedule_uid');
    }

    // pivot ini milik satu group
    public function group()
    {
        return $this->belongsTo(TbGroup::class, 'group_uid');
    }

    // satu schedule_group punya banyak phase
    public function phases()
    {
        // return $this->hasMany(TbPhase::class, 'schedule_group_uid');
        return $this->hasMany(TbPhase::class, 'schedule_group_uid', 'uid')
            ->orderBy('phaseOrder');
    }

    // lewat phases → activities
    public function activities()
    {
        return $this->hasManyThrough(
            TbActivity::class,
            TbPhase::class,
            'schedule_group_uid', // FK di tb_phases
            'phase_uid',          // FK di tb_activities
            'uid',                // PK di tb_schedule_group
            'uid'                 // PK di tb_phases
        );
    }

    public function groupPhases()
    {
        return $this->hasMany(\App\Models\TbScheduleGroupPhase::class, 'schedule_group_uid', 'uid');
    }

    public function links() // assignment per tanggal
    {
        return $this->hasMany(TbScheduleGroupPhase::class, 'schedule_group_uid', 'uid');
    }
}
