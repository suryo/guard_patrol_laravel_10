<?php

namespace App\Models; use Illuminate\Database\Eloquent\Model;
class TbTaskTemplate extends Model{
  protected $table='tb_task_template'; protected $primaryKey='uid'; public $timestamps=false;
  protected $fillable=['taskId','taskName','taskNote','lastUpdated'];
}
