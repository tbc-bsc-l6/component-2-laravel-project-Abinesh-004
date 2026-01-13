<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Module;
use Illuminate\Support\Facades\Auth;

class TeacherModuleController extends Controller
{
    //
    public function show(Module $module)
    {
        $teacher = Auth::user();

        // Verify teacher is assigned to this module
        $isAssigned = $teacher->teacherModules()
            ->where('module_id', $module->id)
            ->exists();

        if (!$isAssigned) {
            abort(403, 'You are not assigned to this module');
        }

        // Separate active and graded students
        $activeStudents = $module->enrollments()
            ->with('user')
            ->where('status', 'enrolled')
            ->orderBy('enrolled_at', 'desc')
            ->get();

        $gradedStudents = $module->enrollments()
            ->with('user')
            ->whereIn('status', ['pass', 'fail'])
            ->orderBy('completed_at', 'desc')
            ->get();

        return view('teacher.modules.show', compact('module', 'activeStudents', 'gradedStudents'));
    }
}
