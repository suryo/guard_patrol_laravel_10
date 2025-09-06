<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbCheckpoint extends Model
{
    protected $table = 'tb_checkpoint';   // <- sesuai tabel Anda
    protected $primaryKey = 'uid';
    public $incrementing = true;
    protected $keyType = 'int';

    // MATIKAN timestamps bawaan (created_at, updated_at)
    public $timestamps = false;

    // Cocokkan dengan kolom sebenarnya di DB
    protected $fillable = [
        'checkpointId',
        'checkpointName',
        'latitude',
        'longitude',
        'address',
        'note',
        'lastUpdated',
    ];

    protected $casts = [
        'latitude'    => 'decimal:6',
        'longitude'   => 'decimal:6',
        'lastUpdated' => 'datetime',
    ];
}
