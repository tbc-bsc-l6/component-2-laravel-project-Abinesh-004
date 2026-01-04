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

        $students = $query->whereHas('role', function($q) {
            $q->whereIn('role', ['student', 'old_student']);
        })->get();

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
