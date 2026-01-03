<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Module::withCount(['activeStudents', 'teachers'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.modules.index', compact('modules'));
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
