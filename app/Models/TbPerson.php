<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbPerson extends Model
{
    protected $table = 'tb_person';
    protected $primaryKey = 'uid';
    public $timestamps = false;            // karena kita pakai lastUpdated
    protected $fillable = ['personId','personName','userName','isDeleted','lastUpdated'];
}
