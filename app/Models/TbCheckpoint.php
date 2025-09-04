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

    protected $primaryKey = 'uid';
public $incrementing = true;
protected $keyType = 'int';

    // Jika primary key bukan "id":
    // protected $primaryKey = 'checkpointId';
    // public $incrementing = false;
    // protected $keyType = 'int';
}
