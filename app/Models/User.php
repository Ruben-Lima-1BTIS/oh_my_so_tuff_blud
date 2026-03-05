<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Authenticatable
{
    use HasUuids;

    protected $fillable = [
        'name',
        'email',
        'password_hash',
        'first_login',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'first_login' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function getRole()
    {
        return $this->role;
    }
}
