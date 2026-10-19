<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category', 'brand', 'model', 'registration_number', 'status'
    ];

    public function instructor(): HasOne
    {
        return $this->hasOne(User::class, 'assigned_vehicle_id');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }
}
