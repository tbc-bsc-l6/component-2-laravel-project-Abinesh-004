<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Module;
use App\Models\Enrollment;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Check if user is old_student
        if ($user->isOldStudent()) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Old students cannot enroll in new modules.');
        }

        $activeCount = $user->activeEnrollments()->count();
        $canEnroll = $activeCount < 4;

        // Get enrolled module IDs
        $enrolledModuleIds = $user->enrollments()
            ->where('status', 'enrolled')
            ->pluck('module_id')
            ->toArray();

        // Get available modules (not enrolled, available, not full)
        $query = Module::where('is_available', true)
            ->whereNotIn('id', $enrolledModuleIds);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('module', 'like', '%' . $searchTerm . '%')
                  ->orWhere('slug', 'like', '%' . $searchTerm . '%');
            });
        }

        $availableModules = $query->get()
            ->filter(function($module) {
                return !$module->isFull();
            });

        return view('student.enroll', compact('availableModules', 'canEnroll', 'activeCount'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Check if user is old_student
        if ($user->isOldStudent()) {
            return redirect()->back()
                ->with('error', 'Old students cannot enroll in new modules.');
        }

        $request->validate([
            'module_id' => 'required|exists:modules,id',
        ]);

        // Check if already at maximum
        if ($user->activeEnrollments()->count() >= 4) {
            return redirect()->back()
                ->with('error', 'You have reached the maximum of 4 active modules!');
        }

        $module = Module::findOrFail($request->module_id);

        // Check if module is available
        if (!$module->is_available) {
            return redirect()->back()
                ->with('error', 'This module is not available for enrollment.');
        }

        // Check if module is full
        if ($module->isFull()) {
            return redirect()->back()
                ->with('error', 'This module is full. Please try again later.');
        }

        // Check if already enrolled
        $exists = Enrollment::where('user_id', $user->id)
            ->where('module_id', $module->id)
            ->where('status', 'enrolled')
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'You are already enrolled in this module!');
        }

        Enrollment::create([
            'user_id' => $user->id,
            'module_id' => $module->id,
            'enrolled_at' => now(),
            'status' => 'enrolled',
        ]);

        return redirect()->route('student.dashboard')
            ->with('success', 'Successfully enrolled in module!');
    }
}
