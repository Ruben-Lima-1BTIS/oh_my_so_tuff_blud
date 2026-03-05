<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'address',
        'email',
        'phone',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function supervisors(): HasMany
    {
        return $this->hasMany(Supervisor::class);
    }

    public function internships(): HasMany
    {
        return $this->hasMany(Internship::class);
    }
}
