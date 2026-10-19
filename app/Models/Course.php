<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category', 'name', 'type', 'start_date',
        'required_theory_hours', 'required_practice_hours'
    ];

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
}
