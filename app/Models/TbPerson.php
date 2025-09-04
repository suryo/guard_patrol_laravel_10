<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TbPerson extends Model
{
    protected $table = 'tb_person';
    protected $primaryKey = 'uid';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = ['personId','personName','userName','isDeleted','lastUpdated'];
}
