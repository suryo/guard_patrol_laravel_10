<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbPerson extends Model
{
    protected $table = 'tb_person';
    protected $fillable = ['uid','personId','personName','userName','isDeleted','lastUpdated'];

    // Jika PK kamu bukan "id", bisa diatur seperti ini (opsional):
    // protected $primaryKey = 'personId';
    // public $incrementing = false;
    // protected $keyType = 'int';
}
