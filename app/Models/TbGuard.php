<?php

// app/Models/TbRouteGuard.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\TbPerson;
use App\Models\TbPersonMapping;

class TbGuard extends Model
{
    protected $table = 'tb_route_guard';
    protected $primaryKey = 'uid';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = ['routeId','personId','mappingId','userName','lastUpdated'];

    // Relasi bantu (berdasar kolom kode varchar, bukan PK uid)
    public function person()
    {
        return $this->belongsTo(TbPerson::class, 'personId', 'personId');
    }

    public function mapping()
    {
        return $this->belongsTo(TbPersonMapping::class, 'mappingId', 'mappingId');
    }
}
