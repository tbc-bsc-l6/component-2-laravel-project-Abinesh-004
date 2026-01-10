<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Module;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        $query = Module::withCount(['activeStudents', 'teachers']);

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('module', 'like', '%' . $searchTerm . '%')
                  ->orWhere('slug', 'like', '%' . $searchTerm . '%');
            });
        }

        $modules = $query->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.modules.index', compact('modules'));
    }

    public function show(Module $module)
    {
        $module->loadCount(['activeStudents', 'teachers']);
        $students = $module->enrollments()
            ->with('user')
            ->orderBy('enrolled_at', 'desc')
            ->get();

        return view('admin.modules.show', compact('module', 'students'));
    }

    public function create()
    {
        return view('admin.modules.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'module' => 'required|string|max:255|unique:modules,module',
        ]);

        Module::create([
            'module' => $request->module,
            'is_available' => true,
        ]);

        return redirect()->route('admin.modules.index')
            ->with('success', 'Module created successfully!');
    }

    public function toggleAvailability(Module $module)
    {
        $module->update([
            'is_available' => !$module->is_available
        ]);

        $status = $module->is_available ? 'available' : 'unavailable';
        return redirect()->back()
            ->with('success', "Module marked as {$status}!");
    }

    public function removeStudent(Module $module, Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $enrollment = $module->enrollments()
            ->where('user_id', $request->user_id)
            ->where('status', 'enrolled')
            ->first();

        if ($enrollment) {
            $enrollment->delete();
            return redirect()->back()
                ->with('success', 'Student removed from module!');
        }

        return redirect()->back()
            ->with('error', 'Enrollment not found!');
    }
}
