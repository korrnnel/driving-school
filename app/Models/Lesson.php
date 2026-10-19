<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id', 'instructor_id', 'vehicle_id', 'booked_by_id',
        'is_additional_hour', 'tasks_planned_count', 'tasks_completed_count',
        'interventions_count', 'critical_mistakes_count', 'minor_mistakes_count',
        'instructor_notes', 'date', 'start_time', 'end_time', 'status'
    ];

    protected $casts = [
        'date' => 'date',
        'is_additional_hour' => 'boolean',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'booked_by_id');
    }
}
