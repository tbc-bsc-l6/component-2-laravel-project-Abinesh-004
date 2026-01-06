{{-- resources/views/student/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Student Dashboard')
@section('page-title', 'Student Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Welcome, {{ auth()->user()->name }}!</h5>
                @if(auth()->user()->isStudent())
                    <p class="card-text">
                        You are currently enrolled in {{ $activeEnrollments->count() }}/4 modules.
                    </p>
                    @if($activeEnrollments->count() < 4)
                        <a href="{{ route('student.enroll.index') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Enroll in More Modules
                        </a>
                    @else
                        <div class="alert alert-warning mb-0 mt-2">
                            <i class="bi bi-exclamation-triangle"></i>
                            You have reached the maximum of 4 active modules. 
                            Complete a module to enroll in new ones.
                        </div>
                    @endif
                @else
                    <p class="card-text">
                        As an old student, you can view your completed module history.
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Active Enrollments --}}
@if(auth()->user()->isStudent() && $activeEnrollments->count() > 0)
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">My Current Modules</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        @foreach($activeEnrollments as $enrollment)
                            <div class="col-md-6">
                                <div class="card h-100 border-primary">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $enrollment->module->module }}</h5>
                                        <p class="card-text">
                                            <i class="bi bi-calendar"></i> 
                                            Enrolled: {{ $enrollment->enrolled_at->format('M d, Y') }}
                                        </p>
                                        <span class="badge bg-primary">In Progress</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Completed Modules --}}
@if($completedEnrollments->count() > 0)
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Completed Modules</h5>
                    <a href="{{ route('student.history') }}" class="btn btn-sm btn-outline-primary">
                        View Full History
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Module</th>
                                    <th>Enrolled Date</th>
                                    <th>Completed Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($completedEnrollments->take(5) as $enrollment)
                                    <tr>
                                        <td>{{ $enrollment->module->module }}</td>
                                        <td>{{ $enrollment->enrolled_at->format('M d, Y') }}</td>
                                        <td>{{ $enrollment->completed_at->format('M d, Y') }}</td>
                                        <td>
                                            @if($enrollment->status === 'pass')
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle"></i> PASS
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle"></i> FAIL
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    @if(auth()->user()->isStudent())
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle"></i>
                    You haven't completed any modules yet. Keep up the good work!
                </div>
            </div>
        </div>
    @endif
@endif
@endsection