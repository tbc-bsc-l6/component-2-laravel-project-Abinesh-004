{{-- resources/views/teacher/modules/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Module Details')
@section('page-title', $module->module)

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">{{ $module->module }}</h5>
                        <p class="text-muted mb-0">
                            <i class="bi bi-people"></i> 
                            {{ $students->where('status', 'enrolled')->count() }} active students
                        </p>
                    </div>
                    <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Students Enrolled</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Enrolled Date</th>
                                <th>Status</th>
                                <th>Completed Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->user->name }}</td>
                                    <td>{{ $enrollment->user->email }}</td>
                                    <td>{{ $enrollment->enrolled_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($enrollment->status === 'enrolled')
                                            <span class="badge bg-primary">Enrolled</span>
                                        @elseif($enrollment->status === 'pass')
                                            <span class="badge bg-success">Pass</span>
                                        @else
                                            <span class="badge bg-danger">Fail</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $enrollment->completed_at ? $enrollment->completed_at->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td>
                                        @if($enrollment->status === 'enrolled')
                                            <button type="button" 
                                                    class="btn btn-sm btn-success" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#gradeModal{{ $enrollment->id }}">
                                                <i class="bi bi-check-circle"></i> Set Grade
                                            </button>
                                        @else
                                            <span class="text-muted">Completed</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Grade Modal -->
                                @if($enrollment->status === 'enrolled')
                                    <div class="modal fade" id="gradeModal{{ $enrollment->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Set Grade for {{ $enrollment->user->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('teacher.grades.update', [$module, $enrollment]) }}" 
                                                      method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Select Grade</label>
                                                            <div class="d-grid gap-2">
                                                                <button type="submit" 
                                                                        name="status" 
                                                                        value="pass" 
                                                                        class="btn btn-success btn-lg">
                                                                    <i class="bi bi-check-circle"></i> PASS
                                                                </button>
                                                                <button type="submit" 
                                                                        name="status" 
                                                                        value="fail" 
                                                                        class="btn btn-danger btn-lg">
                                                                    <i class="bi bi-x-circle"></i> FAIL
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="alert alert-warning" role="alert">
                                                            <i class="bi bi-exclamation-triangle"></i>
                                                            <strong>Note:</strong> Setting a grade will mark this module as completed 
                                                            for the student and timestamp the completion.
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No students enrolled in this module yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Module Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-primary text-white rounded">
                            <h3>{{ $students->where('status', 'enrolled')->count() }}</h3>
                            <p class="mb-0">Currently Enrolled</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-success text-white rounded">
                            <h3>{{ $students->where('status', 'pass')->count() }}</h3>
                            <p class="mb-0">Passed</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-danger text-white rounded">
                            <h3>{{ $students->where('status', 'fail')->count() }}</h3>
                            <p class="mb-0">Failed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection