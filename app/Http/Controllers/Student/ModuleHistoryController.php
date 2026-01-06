<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModuleHistoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $completedEnrollments = $user->completedEnrollments()
            ->with('module')
            ->orderBy('completed_at', 'desc')
            ->get();

        return view('student.history', compact('completedEnrollments'));
    }
}
