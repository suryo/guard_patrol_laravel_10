<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbTaskList extends Model
{
    protected $table = 'tb_task_list';
    protected $primaryKey = 'uid';
    public $timestamps = false;
    protected $fillable = ['taskId', 'scheduleId', 'phaseId', 'taskStatus', 'userName', 'lastUpdated'];


    public function detail()
    {
        return $this->belongsTo(TbTaskGroupDetail::class, 'detail_uid', 'uid');
    }
    // public function getDisplayNameAttribute()
    // {
    //     return $this->taskName
    //         ?? $this->name
    //         ?? ('Task #' . $this->uid);
    // }

     public function getDisplayNameAttribute()
    {
        return $this->taskName ?? $this->name ?? ('Task #'.$this->uid);
    }
}
