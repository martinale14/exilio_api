<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticable implements JwtSubject
{
    use HasFactory;

    protected $hidden = ['password'];

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }
}
