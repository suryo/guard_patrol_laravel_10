<?php

namespace App\Models; use Illuminate\Database\Eloquent\Model;
class TbPhase extends Model{
  protected $table='tb_phase'; protected $primaryKey='uid'; public $timestamps=false;
  protected $fillable=['phaseId','phaseDate','lastUpdated'];
}
