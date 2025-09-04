<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbReport extends Model
{
    protected $table = 'tb_report';

    // Kita pakai reportId (VARCHAR) sebagai PK logis
    protected $primaryKey = 'reportId';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false; // kita kelola lastUpdated sendiri

    protected $fillable = [
        'reportId',
        'reportLatitude',
        'reportLongitude',
        'activityId',
        'personId',
        'checkpointName',
        'reportNote',
        'reportDate',
        'reportTime',
        'lastUpdated',
    ];
}
