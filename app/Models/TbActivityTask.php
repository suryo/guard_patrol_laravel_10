<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbActivityTask extends Model
{
    protected $table = 'tb_activity_task';
    protected $primaryKey = 'uid';
    public $timestamps = true; // created_at, updated_at tersedia

    protected $fillable = [
        'activity_uid','task_uid','is_done','checked_at','notes'
    ];

    protected $casts = [
        'is_done'   => 'boolean',
        'checked_at'=> 'datetime',
    ];

    public function activity()
    {
        return $this->belongsTo(TbActivity::class, 'activity_uid');
    }
}
