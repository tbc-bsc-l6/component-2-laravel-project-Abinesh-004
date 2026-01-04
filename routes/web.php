<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ModuleController;


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
    
});




require __DIR__.'/auth.php';
