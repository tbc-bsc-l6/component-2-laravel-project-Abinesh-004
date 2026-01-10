<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Module;
use App\Models\UserRole;
use App\Models\TeacherModule;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('teacherModules.module');

        // Filter by role (teachers only)
        $query->whereHas('role', function($q) {
            $q->where('role', 'teacher');
        });

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by assigned module
        if ($request->has('module_filter') && $request->module_filter != '') {
            $query->whereHas('teacherModules', function($q) use ($request) {
                $q->where('module_id', $request->module_filter);
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');

        if ($sortBy === 'module_count') {
            // Sort by module count - need to get all first, then paginate manually
            $query->withCount('teacherModules');
            $allTeachers = $query->get()->sortBy('teacher_modules_count', SORT_REGULAR, $sortOrder === 'desc')->values();
            
            // Manual pagination
            $perPage = 10;
            $currentPage = $request->get('page', 1);
            $offset = ($currentPage - 1) * $perPage;
            
            $teachers = new \Illuminate\Pagination\LengthAwarePaginator(
                $allTeachers->slice($offset, $perPage)->values(),
                $allTeachers->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            // Sort by name or email with pagination
            $query->orderBy($sortBy, $sortOrder);
            $teachers = $query->paginate(10)->appends($request->query());
        }

        $modules = Module::all();

        return view('admin.teachers.index', compact('teachers', 'modules'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $teacherRole = UserRole::where('role', 'teacher')->first();

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_role_id' => $teacherRole->id,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully!');
    }

    public function destroy(User $teacher)
    {
        if (!$teacher->isTeacher()) {
            return redirect()->back()
                ->with('error', 'User is not a teacher!');
        }

        $teacher->delete();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher removed successfully!');
    }

    public function attachModule(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'module_id' => 'required|exists:modules,id',
        ]);

        $teacher = User::findOrFail($request->teacher_id);

        if (!$teacher->isTeacher()) {
            return redirect()->back()
                ->with('error', 'User is not a teacher!');
        }

        // Check if already attached
        $exists = TeacherModule::where('user_id', $request->teacher_id)
            ->where('module_id', $request->module_id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Teacher already assigned to this module!');
        }

        TeacherModule::create([
            'user_id' => $request->teacher_id,
            'module_id' => $request->module_id,
        ]);

        return redirect()->back()
            ->with('success', 'Teacher assigned to module successfully!');
    }

    public function detachModule(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'module_id' => 'required|exists:modules,id',
        ]);

        TeacherModule::where('user_id', $request->teacher_id)
            ->where('module_id', $request->module_id)
            ->delete();

        return redirect()->back()
            ->with('success', 'Teacher removed from module!');
    }
}
