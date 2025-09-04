<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbCheckpoint extends Model
{
    protected $table = 'tb_checkpoint';
    protected $fillable = [
        'uid','checkpointId','checkpointName','checkLatitude','checkLongitude',
        'checkStatus','isDeleted','userName','lastUpdated'
    ];

    // Jika primary key bukan "id":
    // protected $primaryKey = 'checkpointId';
    // public $incrementing = false;
    // protected $keyType = 'int';
}
