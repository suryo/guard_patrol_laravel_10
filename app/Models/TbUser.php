<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class TbUser extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'tb_users';
    protected $primaryKey = 'uid';   // sesuaikan
    public $timestamps = false;

    protected $fillable = [
        'userId',
        'userName',
        'userPassword',
        'userLevel',
        'hashMobile',
        'hashWeb',
        'userEmail',
    ];

    protected $hidden = ['userPassword'];

    public function getAuthPassword()
    {
        return $this->userPassword;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return ['userName' => $this->userName, 'userLevel' => $this->userLevel];
    }
}
