<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasRoles;
    protected $guard_name = 'api';
    protected $fillable = [
        'name', 'email', 'phone', 'password',
    ];

    protected $hidden = [
        'password',
    ];
// app/Models/User.php
    public function merchant()
    {
        return $this->hasOne(Merchant::class);
    }

    // JWT required methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
