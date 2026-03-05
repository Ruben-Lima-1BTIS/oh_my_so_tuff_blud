<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Internship extends Model
{
    use HasUuids;

    protected $fillable = [
        'company_id',
        'title',
        'start_date',
        'end_date',
        'total_hours_required',
        'min_hours_day',
        'lunch_break_minutes',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_hours_required' => 'integer',
        'min_hours_day' => 'decimal:1',
        'lunch_break_minutes' => 'integer',
        'created_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function studentAssignments(): HasMany
    {
        return $this->hasMany(StudentInternship::class);
    }

    public function supervisorAssignments(): HasMany
    {
        return $this->hasMany(SupervisorInternship::class);
    }

    public function hours(): HasMany
    {
        return $this->hasMany(Hour::class);
    }
}
