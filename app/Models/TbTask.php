<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbTask extends Model
{
    protected $table = 'tb_task';
    protected $primaryKey = 'uid';
    public $timestamps = false;
    protected $fillable = ['taskId', 'taskName', 'taskNote', 'userName', 'lastUpdated'];

    public function groups()
    {
        return $this->belongsToMany(TbTaskGroup::class, 'tb_task_group_detail', 'task_uid', 'group_uid')
            ->withPivot(['sortOrder', 'userName', 'lastUpdated'])
            ->as('pivot_detail');
    }

    public function getDisplayNameAttribute()
    {
        return $this->taskName ?? $this->name ?? ('Task #' . $this->uid);
    }
}
