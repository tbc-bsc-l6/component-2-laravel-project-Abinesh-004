<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\Enrollment;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;

class StudentGradeController extends Controller
{
    public function update(Request $request, Module $module, Enrollment $enrollment)
    {
        $teacher = Auth::user();

        // Verify teacher is assigned to this module
        $isAssigned = $teacher->teacherModules()
            ->where('module_id', $module->id)
            ->exists();

        if (!$isAssigned) {
            abort(403, 'You are not assigned to this module');
        }

        // Verify enrollment belongs to this module
        if ($enrollment->module_id !== $module->id) {
            abort(404);
        }

        $request->validate([
            'status' => 'required|in:pass,fail',
        ]);

        $enrollment->update([
            'status' => $request->status,
            'completed_at' => now(),
        ]);

        // Check if student has any active enrollments
        $student = $enrollment->user;
        $hasActiveEnrollments = $student->activeEnrollments()->count() > 0;

        // If no active enrollments and has completed modules, change to old_student
        if (!$hasActiveEnrollments) {
            $hasCompletedModules = $student->completedEnrollments()->count() > 0;
            
            if ($hasCompletedModules) {
                $oldStudentRole = UserRole::where('role', 'old_student')->first();
                $student->update([
                    'user_role_id' => $oldStudentRole->id
                ]);
            }
        }

        return redirect()->back()
            ->with('success', 'Student grade updated successfully!');
    }
}
