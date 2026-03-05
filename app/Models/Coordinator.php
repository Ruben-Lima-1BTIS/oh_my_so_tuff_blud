<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coordinator extends Model
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

    public function classes(): HasMany
    {
        return $this->hasMany(ClassModel::class);
    }
}
