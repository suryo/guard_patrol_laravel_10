<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbScheduleTemplate extends Model
{
    protected $table = 'tb_schedule_template';
    protected $primaryKey = 'uid';
    public $timestamps = false;
    protected $fillable = [
        'templateId',       // <- tetap ada
        'templateName',
        'personId',
        'timeStart',
        'timeEnd',
        'userName',
        'lastUpdated',
    ];

    // Generator ID: SCHDL-TMPLT-##### (unik)
    public static function generateUniqueId(): string
    {
        do {
            $id = 'SCHDL-TMPLT-' . str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT);
        } while (self::where('templateId', $id)->exists());

        return $id;
    }

    protected static function booted()
    {
        static::creating(function ($m) {
            if (empty($m->templateId)) {
                $m->templateId = self::generateUniqueId();
            }
            // jaga-jaga update kolom audit
            if (empty($m->lastUpdated)) {
                $m->lastUpdated = now();
            }
            if (empty($m->userName) && auth()->check()) {
                $m->userName = auth()->user()->name;
            }
        });

        static::updating(function ($m) {
            $m->lastUpdated = now();
        });
    }


    public function taskDetails()
    {
        // many-to-many via pivot tb_schedule_template_detail
        return $this->belongsToMany(
            TbTaskGroupDetail::class,
            'tb_schedule_template_detail',
            'template_uid',
            'task_group_detail_uid'
        )
            ->withPivot(['uid', 'sortOrder'])
            ->withTimestamps();
    }

    // jika ingin akses langsung ke baris pivot
    public function details()
    {
        return $this->hasMany(TbScheduleTemplateDetail::class, 'template_uid', 'uid');
    }
}
