<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TbSchedule extends Model {
  protected $table='tb_schedule'; protected $primaryKey='uid'; public $timestamps=false;
  protected $fillable=[
    'scheduleId','mappingId','personId','activityId','checkpointName','scheduleName',
    'scheduleStart','scheduleEnd','scheduleDate','userName','lastUpdated'
  ];
}
