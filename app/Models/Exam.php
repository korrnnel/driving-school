<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id', 'type', 'instructor_id', 'category', 'result', 'date'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
