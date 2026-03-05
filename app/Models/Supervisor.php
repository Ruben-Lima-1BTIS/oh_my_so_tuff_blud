<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Supervisor extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'email',
        'password_hash',
        'company_id',
        'first_login',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'first_login' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function internship(): HasOne
    {
        return $this->hasOne(SupervisorInternship::class);
    }
}
