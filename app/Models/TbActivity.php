<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbActivity extends Model
{
    protected $table = 'tb_activity';

    // uid kemungkinan auto-increment; PK logis kita pakai activityId (string)
    protected $primaryKey = 'activityId';
    public $incrementing = false;    // activityId bukan auto-increment
    protected $keyType = 'string';   // karena VARCHAR(20)

    protected $fillable = [
        'activityId',       // string
        'personId',         // string (varchar(2))
        'scheduleId',       // string (varchar(20))
        'checkpointStart',  // string
        'checkpointEnd',    // string|null
        'activityStart',    // datetime
        'activityEnd',      // datetime|null
        'activityStatus',   // 'I'|'D'|'S'|'F'
        'lastUpdated',      // timestamp
    ];

    public $timestamps = false; // kita kelola lastUpdated sendiri
}
