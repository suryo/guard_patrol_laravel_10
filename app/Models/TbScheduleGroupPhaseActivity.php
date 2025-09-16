<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbScheduleGroupPhaseActivity extends Model
{
    protected $table = 'tb_schedule_group_phase_activity';
    protected $primaryKey = 'uid';

    protected $fillable = [
        'phase_uid',
        'task_group_uid',
        'task_group_detail_uid',
        'task_uid',
        'sortOrder',
        'activityNote',
        'userName',
    ];

    // === RELASI ===

    // ke Phase
    public function phase()
    {
        return $this->belongsTo(TbPhase::class, 'phase_uid');
    }

    // ke Task Group (pos)
    public function taskGroup()
    {
        return $this->belongsTo(TbTaskGroup::class, 'task_group_uid');
    }

    // ke Task Group Detail
    public function taskGroupDetail()
    {
        return $this->belongsTo(TbTaskGroupDetail::class, 'task_group_detail_uid');
    }

    // ke Task
    public function task()
    {
        return $this->belongsTo(TbTask::class, 'task_uid');
    }
}
