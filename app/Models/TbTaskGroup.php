<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbTaskGroup extends Model
{
    protected $table = 'tb_task_group';
    protected $primaryKey = 'uid';
    public $timestamps = false; // hanya pakai lastUpdated

    protected $fillable = [
        'groupId',
        'groupName',
        'groupNote',
        'userName',
        'lastUpdated',
    ];

    // relasi ke detail (pivot ke task)
    public function details()
    {
        // return $this->hasMany(\App\Models\TbTaskGroupDetail::class, 'group_uid');

        // urut sesuai kolom yang ada: 'sortOrder' kalau ada, fallback 'uid'
        return $this->hasMany(TbTaskGroupDetail::class, 'group_uid', 'uid')
                    ->orderByRaw("COALESCE(sortOrder, uid)");

    }

    // relasi many-to-many ke task melalui detail
    public function tasks()
    {
        return $this->belongsToMany(\App\Models\TbTask::class, 'tb_task_group_detail', 'group_uid', 'task_uid')
            ->withPivot(['sortOrder', 'userName', 'lastUpdated'])
            ->as('pivot_detail');
    }

    // public function getDisplayNameAttribute()
    // {
    //     return $this->groupName
    //         ?? $this->name
    //         ?? ('Group #' . $this->uid);
    // }
    public function getDisplayNameAttribute()
    {
        return $this->groupName ?? $this->name ?? ('Pos '.$this->uid);
    }
}
