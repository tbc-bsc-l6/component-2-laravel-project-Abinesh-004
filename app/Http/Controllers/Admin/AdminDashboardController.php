<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Module;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_teachers' => User::whereHas('role', function($q) {
                $q->where('role', 'teacher');
            })->count(),
            'total_students' => User::whereHas('role', function($q) {
                $q->where('role', 'student');
            })->count(),
            'total_modules' => Module::count(),
            'active_modules' => Module::where('is_available', true)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

}
