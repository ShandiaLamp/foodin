<?php

namespace Shandialamp\Foodin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    protected $table = 'admin_users';

    protected $fillable = ['username', 'mobile', 'password', 'real_name'];

    protected $hidden = ['password'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function tokens()
    {
        return $this->hasMany(UserToken::class);
    }
}
