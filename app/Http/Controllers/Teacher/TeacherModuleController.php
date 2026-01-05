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

        $students = $module->enrollments()
            ->with('user')
            ->get();

        return view('teacher.modules.show', compact('module', 'students'));
    }
}
