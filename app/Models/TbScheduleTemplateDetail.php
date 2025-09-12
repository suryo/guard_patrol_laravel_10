<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbScheduleTemplateDetail extends Model
{
    protected $table = 'tb_schedule_template_detail';
    protected $primaryKey = 'uid';
    public $timestamps = true;

    protected $fillable = ['template_uid', 'task_group_detail_uid', 'sortOrder'];
}
