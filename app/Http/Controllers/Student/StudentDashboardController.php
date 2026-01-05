<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $activeEnrollments = $user->activeEnrollments()
            ->with('module')
            ->get();

        $completedEnrollments = $user->completedEnrollments()
            ->with('module')
            ->orderBy('completed_at', 'desc')
            ->get();

        return view('student.dashboard', compact('activeEnrollments', 'completedEnrollments'));
    }
}
