<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TbActivity extends Model {
  protected $table='tb_activity'; protected $primaryKey='uid'; public $timestamps=false;
  protected $fillable=['activityId','personId','scheduleId','checkpointStart','checkpointEnd','activityStart','activityEnd','activityStatus','lastUpdated'];
}
