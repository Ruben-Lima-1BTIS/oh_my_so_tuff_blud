<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassModel extends Model
{
    use HasUuids;

    protected $table = 'classes';

    protected $fillable = [
        'course',
        'sigla',
        'year',
        'coordinator_id',
    ];

    protected $casts = [
        'year' => 'integer',
        'created_at' => 'datetime',
    ];

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(Coordinator::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }
}
