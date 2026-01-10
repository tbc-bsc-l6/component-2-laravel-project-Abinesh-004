<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'module',
        'is_available',
        'slug',
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

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Module $module) {
            if (empty($module->slug)) {
                $module->slug = \Illuminate\Support\Str::slug($module->module);
            }
        });

        static::updating(function (Module $module) {
            if ($module->isDirty('module') && empty($module->slug)) {
                $module->slug = \Illuminate\Support\Str::slug($module->module);
            }
        });
    }
}
