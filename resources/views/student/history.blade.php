{{-- resources/views/student/history.blade.php --}}
@extends('layouts.app')

@section('title', 'Module History')
@section('page-title', 'Module History')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">My Module History</h5>
                <p class="card-text">
                    View all your completed modules and their results.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Completed Modules</h5>
            </div>
            <div class="card-body">
                @if($completedEnrollments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Module Name</th>
                                    <th>Enrolled Date</th>
                                    <th>Completed Date</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($completedEnrollments as $index => $enrollment)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $enrollment->module->module }}</strong>
                                            @if(!$enrollment->module->is_available)
                                                <span class="badge bg-secondary ms-2">Archived</span>
                                            @endif
                                        </td>
                                        <td>{{ $enrollment->enrolled_at->format('M d, Y') }}</td>
                                        <td>{{ $enrollment->completed_at->format('M d, Y') }}</td>
                                        <td>
                                            @php
                                                $duration = $enrollment->enrolled_at->diffInDays($enrollment->completed_at);
                                            @endphp
                                            {{ $duration }} {{ Str::plural('day', $duration) }}
                                        </td>
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

                    {{-- Statistics --}}
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-primary">{{ $completedEnrollments->count() }}</h3>
                                    <p class="mb-0">Total Completed</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-success">{{ $completedEnrollments->where('status', 'pass')->count() }}</h3>
                                    <p class="mb-0">Passed</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-danger">{{ $completedEnrollments->where('status', 'fail')->count() }}</h3>
                                    <p class="mb-0">Failed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle"></i>
                        You haven't completed any modules yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection