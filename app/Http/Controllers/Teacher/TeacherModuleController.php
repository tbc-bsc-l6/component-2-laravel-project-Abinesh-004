<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Module;
use Illuminate\Support\Facades\Auth;

class TeacherModuleController extends Controller
{
    //
    public function show(Request $request, Module $module)
    {
        $teacher = Auth::user();

        // Verify teacher is assigned to this module
        $isAssigned = $teacher->teacherModules()
            ->where('module_id', $module->id)
            ->exists();

        if (!$isAssigned) {
            abort(403, 'You are not assigned to this module');
        }

        $search = $request->input('search');
        $statusFilter = $request->input('status', 'all');

        // Base query for active students
        $activeQuery = $module->enrollments()
            ->with('user')
            ->where('status', 'enrolled');

        // Base query for graded students
        $gradedQuery = $module->enrollments()
            ->with('user')
            ->whereIn('status', ['pass', 'fail']);

        // Apply search to both queries
        if ($search) {
            $activeQuery->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });

            $gradedQuery->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Apply status filter
        if ($statusFilter !== 'all') {
            if ($statusFilter === 'enrolled') {
                $gradedQuery->whereRaw('1 = 0'); // Return no results
            } elseif (in_array($statusFilter, ['pass', 'fail'])) {
                $activeQuery->whereRaw('1 = 0'); // Return no results
                $gradedQuery->where('status', $statusFilter);
            }
        }

        // Paginate results
        $activeStudents = $activeQuery
            ->orderBy('enrolled_at', 'desc')
            ->paginate(10, ['*'], 'active_page')
            ->appends($request->except('active_page'));

        $gradedStudents = $gradedQuery
            ->orderBy('completed_at', 'desc')
            ->paginate(10, ['*'], 'graded_page')
            ->appends($request->except('graded_page'));

        return view('teacher.modules.show', compact('module', 'activeStudents', 'gradedStudents', 'search', 'statusFilter'));
    }
}
