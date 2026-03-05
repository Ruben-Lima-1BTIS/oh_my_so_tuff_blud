<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'email',
        'password_hash',
        'class_id',
        'first_login',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'first_login' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function internship(): HasOne
    {
        return $this->hasOne(StudentInternship::class);
    }

    public function hours(): HasMany
    {
        return $this->hasMany(Hour::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
