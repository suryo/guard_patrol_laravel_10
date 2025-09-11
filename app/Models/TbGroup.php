<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbGroup extends Model
{
    protected $table = 'tb_groups';
    protected $primaryKey = 'uid';
    public $timestamps = false; // hanya ada lastUpdated manual

    protected $fillable = [
        'groupId',
        'groupName',
        'timeStart',
        'timeEnd',
        'note',
        'lastUpdated',
    ];

    // === RELASI ===

    // group dipakai di banyak schedule melalui pivot
    public function schedules()
    {
        return $this->belongsToMany(TbSchedule::class, 'tb_schedule_group', 'group_uid', 'schedule_uid')
                    ->withPivot(['timeStart','timeEnd','sortOrder'])
                    ->withTimestamps();
    }

    // akses pivot schedule_group langsung
    public function scheduleGroups()
    {
        return $this->hasMany(TbScheduleGroup::class, 'group_uid');
    }

    // lewat schedule_group -> phases
    public function phases()
    {
        return $this->hasManyThrough(
            TbPhase::class,
            TbScheduleGroup::class,
            'group_uid',          // FK di tb_schedule_group
            'schedule_group_uid', // FK di tb_phases
            'uid',                // PK di tb_groups
            'uid'                 // PK di tb_schedule_group
        );
    }
}
