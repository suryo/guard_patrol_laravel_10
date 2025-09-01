<?php

namespace App\Models; use Illuminate\Database\Eloquent\Model;
class TbLogs extends Model{
  protected $table='tb_logs'; protected $primaryKey='uid'; public $timestamps=false;
  protected $fillable=['activity','category','userName','note','lastUpdated'];
}
