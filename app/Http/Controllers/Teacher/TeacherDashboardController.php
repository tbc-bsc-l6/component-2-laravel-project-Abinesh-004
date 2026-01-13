<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        
        $modules = $teacher->teacherModules()
            ->with(['module' => function($query) {
                $query->withCount('activeStudents');
            }])
            ->get()
            ->pluck('module');

        return view('teacher.dashboard', compact('modules'));
    }
}
