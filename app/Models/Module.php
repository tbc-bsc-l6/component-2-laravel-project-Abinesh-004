<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'module',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    const MAX_STUDENTS = 10;

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_modules');
    }

    // Get current active students
    public function activeStudents()
    {
        return $this->enrollments()->where('status', 'enrolled')->with('user');
    }

    // Check if module is full
    public function isFull()
    {
        return $this->activeStudents()->count() >= self::MAX_STUDENTS;
    }

    // Get available spots
    public function availableSpots()
    {
        return self::MAX_STUDENTS - $this->activeStudents()->count();
    }
}
