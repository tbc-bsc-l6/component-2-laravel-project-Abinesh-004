<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
use App\Http\Controllers\Teacher\TeacherModuleController;
use App\Http\Controllers\Teacher\StudentGradeController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\EnrollmentController;
use App\Http\Controllers\Student\ModuleHistoryController;

Route::get('/', function () {
    return view('auth.login');
});


// Redirect after login based on role
Route::get('/home', function () {
    $user = auth()->user();
    
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isTeacher()) {
        return redirect()->route('teacher.dashboard');
    } elseif ($user->isStudent() || $user->isOldStudent()) {
        return redirect()->route('student.dashboard');
    }
    
    return redirect('/');
})->middleware('auth')->name('home');


// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Modules
    Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::get('/modules/create', [ModuleController::class, 'create'])->name('modules.create');
    Route::post('/modules', [ModuleController::class, 'store'])->name('modules.store');
    Route::patch('/modules/{module}/toggle', [ModuleController::class, 'toggleAvailability'])->name('modules.toggle');
    Route::delete('/modules/{module}/students', [ModuleController::class, 'removeStudent'])->name('modules.remove-student');
    
    // Teachers
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
    
    // Specific routes must come before wildcard routes like {teacher}
    Route::post('/teachers/attach-module', [TeacherController::class, 'attachModule'])->name('teachers.attach-module');
    Route::delete('/teachers/detach-module', [TeacherController::class, 'detachModule'])->name('teachers.detach-module');
    
    Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');
    
    // Students
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::patch('/students/{user}/change-role', [StudentController::class, 'changeRole'])->name('students.change-role');
});

// Teacher Routes
Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::get('/modules/{module}', [TeacherModuleController::class, 'show'])->name('modules.show');
    Route::patch('/modules/{module}/enrollments/{enrollment}', [StudentGradeController::class, 'update'])->name('grades.update');
});

// Student Routes
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/enroll', [EnrollmentController::class, 'index'])->name('enroll.index');
    Route::post('/enroll', [EnrollmentController::class, 'store'])->name('enroll.store');
    Route::get('/history', [ModuleHistoryController::class, 'index'])->name('history');
});

// Profile Routes (accessible to all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
