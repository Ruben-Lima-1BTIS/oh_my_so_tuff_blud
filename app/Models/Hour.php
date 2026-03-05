<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hour extends Model
{
    use HasUuids;

    protected $fillable = [
        'student_id',
        'internship_id',
        'date',
        'start_time',
        'end_time',
        'duration_hours',
        'status',
        'supervisor_reviewed_by',
        'supervisor_comment',
        'reviewed_at',
    ];

    protected $casts = [
        'date' => 'date',
        'duration_hours' => 'decimal:1',
        'created_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function internship(): BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Supervisor::class, 'supervisor_reviewed_by');
    }
}
