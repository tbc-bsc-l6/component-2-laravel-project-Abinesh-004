<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRole;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['role', 'activeEnrollments.module']);

        // Filter by role (students and old students)
        $query->whereHas('role', function($q) use ($request) {
            $roles = ['student', 'old_student'];
            
            // Apply role filter if specified
            if ($request->has('role_filter') && $request->role_filter != '') {
                $q->where('role', $request->role_filter);
            } else {
                $q->whereIn('role', $roles);
            }
        });

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('role', function($roleQuery) use ($searchTerm) {
                      $roleQuery->where('role', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Filter by enrollment status
        if ($request->has('enrollment_filter') && $request->enrollment_filter != '') {
            if ($request->enrollment_filter === 'enrolled') {
                $query->has('activeEnrollments');
            } elseif ($request->enrollment_filter === 'not_enrolled') {
                $query->doesntHave('activeEnrollments');
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');

        if ($sortBy === 'enrollment_count') {
            // Sort by enrollment count using withCount
            $query->withCount('activeEnrollments');
            $students = $query->get()->sortBy('active_enrollments_count', SORT_REGULAR, $sortOrder === 'desc')->values();
        } elseif ($sortBy === 'role') {
            // Sort by role through relationship
            $query->join('user_roles', 'users.user_role_id', '=', 'user_roles.id')
                  ->orderBy('user_roles.role', $sortOrder)
                  ->select('users.*');
            $students = $query->get();
        } else {
            // Sort by name or email
            $query->orderBy($sortBy, $sortOrder);
            $students = $query->get();
        }

        return view('admin.students.index', compact('students'));
    }

    public function changeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:student,old_student,teacher',
        ]);

        $newRole = UserRole::where('role', $request->role)->first();

        $user->update([
            'user_role_id' => $newRole->id
        ]);

        return redirect()->back()
            ->with('success', 'User role updated successfully!');
    }

}
