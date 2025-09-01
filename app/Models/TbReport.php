<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TbReport extends Model {
  protected $table='tb_report'; protected $primaryKey='uid'; public $timestamps=false;
  protected $fillable=['reportId','reportLatitude','reportLongitude','activityId','personId','checkpointName','reportNote','reportDate','reportTime','lastUpdated'];
}
