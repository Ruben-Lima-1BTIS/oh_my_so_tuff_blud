<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentInternship extends Model
{
    use HasUuids;

    protected $fillable = [
        'student_id',
        'internship_id',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    const UPDATED_AT = null;
    const CREATED_AT = 'assigned_at';

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function internship(): BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }
}
