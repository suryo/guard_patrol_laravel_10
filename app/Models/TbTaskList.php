<?php

namespace App\Models; use Illuminate\Database\Eloquent\Model;
class TbTaskList extends Model{
  protected $table='tb_task_list'; protected $primaryKey='uid'; public $timestamps=false;
  protected $fillable=['taskId','scheduleId','phaseId','taskStatus','userName','lastUpdated'];
}
