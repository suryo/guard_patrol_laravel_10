<?php

namespace App\Models; use Illuminate\Database\Eloquent\Model;
class TbPersonMapping extends Model{
  protected $table='tb_person_mapping'; protected $primaryKey='uid'; public $timestamps=false;
  protected $fillable=['mappingId','mappingTag','mappingName','userName','lastUpdated'];
}
