<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TbUser extends Model {
  protected $table='tb_users'; protected $primaryKey='uid'; public $timestamps=false;
  protected $fillable=['userId','userName','userPassword','userLevel','hashMobile','hashWeb','userEmail','lastUpdated'];
  protected $hidden=['userPassword'];
}
