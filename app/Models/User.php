<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'surname', 'email', 'phone', 'password',
        'role', 'license_number', 'assigned_vehicle_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function assignedVehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'assigned_vehicle_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function lessonsAsInstructor(): HasMany
    {
        return $this->hasMany(Lesson::class, 'instructor_id');
    }

    public function lessonsAsStudent(): HasMany
    {
        return $this->hasMany(Lesson::class, 'booked_by_id');
    }
}
