<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

     use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'user_role_id',
    ];



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function role()
    {
        return $this->belongsTo(UserRole::class, 'user_role_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function teacherModules()
    {
        return $this->hasMany(TeacherModule::class);
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role->role === 'admin';
    }

    public function isTeacher()
    {
        return $this->role->role === 'teacher';
    }

    public function isStudent()
    {
        return $this->role->role === 'student';
    }

    public function isOldStudent()
    {
        return $this->role->role === 'old_student';
    }

    // Get active enrollments
    public function activeEnrollments()
    {
        return $this->enrollments()->where('status', 'enrolled');
    }

    // Get completed modules
    public function completedEnrollments()
    {
        return $this->enrollments()->whereIn('status', ['pass', 'fail']);
    }
}
