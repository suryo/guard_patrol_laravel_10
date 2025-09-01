<?php

namespace App\Models; use Illuminate\Database\Eloquent\Model;
class TbScheduleTemplate extends Model{
  protected $table='tb_schedule_template'; protected $primaryKey='uid'; public $timestamps=false;
  protected $fillable=['templateId','templateName','templatePhase','templateMapping','templatePerson','templateStart','templateEnd','templateTask','userName','lastUpdated'];
}
