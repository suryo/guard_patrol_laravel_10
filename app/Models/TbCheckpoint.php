<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbCheckpoint extends Model
{
    protected $table = 'tb_checkpoint';
    protected $primaryKey = 'uid';
    public $timestamps = false;
    protected $fillable = [
        'checkpointId','checkpointName','latitude','longitude','address','note','lastUpdated'
    ];
}

