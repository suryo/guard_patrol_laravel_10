<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbTaskGroupDetail extends Model
{
    protected $table = 'tb_task_group_detail';
    protected $primaryKey = 'uid';
    public $timestamps = false;

    protected $fillable = [
        'group_uid',
        'task_uid',
        'sortOrder',
        'userName',
        'lastUpdated',
    ];

    public function group()
    {
        return $this->belongsTo(TbTaskGroup::class, 'group_uid', 'uid');
    }

     public function task()
    {
        // Ganti 'task_uid' jika FK di tb_task_group_detail Anda berbeda
        return $this->belongsTo(TbTask::class, 'task_uid', 'uid');
    }

    public function tasks()
    {
        return $this->hasMany(TbTaskList::class, 'task_uid', 'uid');
    }



    //  public function tasks()
    // {
    //     // GANTI 'detail_uid' jika FK di tb_task_list berbeda
    //     return $this->hasMany(TbTaskList::class, 'detail_uid', 'uid');
    // }

    public function scheduleTemplates()
    {
        return $this->belongsToMany(
            TbScheduleTemplate::class,
            'tb_schedule_template_detail',
            'task_group_detail_uid',
            'template_uid'
        )
        ->withPivot(['uid','sortOrder'])
        ->withTimestamps();
    }

    public function getDisplayNameAttribute()
    {
        return $this->detailName
            ?? $this->name
            ?? ('Detail #'.$this->uid);
    }
}
