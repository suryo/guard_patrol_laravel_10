<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbScheduleGroupPhase extends Model
{
    protected $table = 'tb_schedule_group_phase';
    protected $primaryKey = 'uid';
    protected $fillable = ['schedule_group_uid', 'phase_uid', 'phaseDate', 'sortOrder'];

    public function group()
    { // tb_schedule_group
        return $this->belongsTo(TbScheduleGroup::class, 'schedule_group_uid', 'uid');
    }
    public function phase()
    { // tb_phase (master)
        return $this->belongsTo(TbPhase::class, 'phase_uid', 'uid');
    }
    public function scheduleGroup()
    {
        return $this->belongsTo(TbScheduleGroup::class, 'schedule_group_uid', 'uid');
    }
}
