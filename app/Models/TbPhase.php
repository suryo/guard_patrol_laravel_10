<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbPhase extends Model
{
    // ganti ke nama tabel sebenarnya
    protected $table = 'tb_phases';

    protected $primaryKey = 'uid';
    public $timestamps = false; // tabel ini pakai lastUpdated sendiri

    protected $fillable = [
        'phaseId',
        'schedule_group_uid',
        'phaseDate',
        'lastUpdated',
        'phaseName',
        'phaseOrder',
    ];
    public function scheduleGroup()
    {
        return $this->belongsTo(TbScheduleGroup::class, 'schedule_group_uid', 'uid');
    }

    public function groupLinks()
    {
        return $this->hasMany(\App\Models\TbScheduleGroupPhase::class, 'phase_uid', 'uid');
    }

    // public function links()
    // {
    //     return $this->hasMany(TbScheduleGroupPhase::class, 'phase_uid', 'uid');
    // }
}
