{{-- resources/views/teacher/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Teacher Dashboard')
@section('page-title', 'Teacher Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Welcome, {{ auth()->user()->name }}!</h5>
                <p class="card-text">Here are the modules assigned to you.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">My Modules</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    @forelse($modules as $module)
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $module->module }}</h5>
                                    <p class="card-text">
                                        <i class="bi bi-people"></i> 
                                        Active Students: {{ $module->active_students_count }}/10
                                    </p>
                                    <div class="progress mb-3">
                                        @php
                                            $percentage = ($module->active_students_count / 10) * 100;
                                        @endphp
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: {{ $percentage }}%">
                                            {{ $module->active_students_count }}
                                        </div>
                                    </div>
                                    <a href="{{ route('teacher.modules.show', $module) }}" 
                                       class="btn btn-primary w-100">
                                        <i class="bi bi-eye"></i> View Students
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-md-12">
                            <div class="alert alert-info" role="alert">
                                <i class="bi bi-info-circle"></i> You have no modules assigned yet. 
                                Please contact an administrator.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
