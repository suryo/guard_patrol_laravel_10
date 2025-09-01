<?php

namespace App\Models; use Illuminate\Database\Eloquent\Model;
class TbTask extends Model{
  protected $table='tb_task'; protected $primaryKey='uid'; public $timestamps=false;
  protected $fillable=['taskId','taskName','taskNote','userName','lastUpdated'];
}
