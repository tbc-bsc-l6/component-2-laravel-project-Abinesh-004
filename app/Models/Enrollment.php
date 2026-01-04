<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = [
        'user_id',
        'module_id',
        'enrolled_at',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    // Check if enrollment is active
    public function isActive()
    {
        return $this->status === 'enrolled';
    }

    // Check if completed
    public function isCompleted()
    {
        return in_array($this->status, ['pass', 'fail']);
    }
}
