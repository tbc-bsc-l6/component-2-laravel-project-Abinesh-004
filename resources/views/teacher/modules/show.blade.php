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
                            {{ $activeStudents->count() }} active students
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

{{-- Search and Filter Form --}}
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('teacher.modules.show', $module) }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="search" class="form-label">Search Students</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" 
                                       class="form-control" 
                                       id="search"
                                       name="search" 
                                       placeholder="Search by name or email..." 
                                       value="{{ $search }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">Filter by Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All Students</option>
                                <option value="enrolled" {{ $statusFilter === 'enrolled' ? 'selected' : '' }}>Enrolled Only</option>
                                <option value="pass" {{ $statusFilter === 'pass' ? 'selected' : '' }}>Passed Only</option>
                                <option value="fail" {{ $statusFilter === 'fail' ? 'selected' : '' }}>Failed Only</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-funnel"></i> Apply
                            </button>
                        </div>
                    </div>
                    @if($search || $statusFilter !== 'all')
                        <div class="mt-3">
                            <a href="{{ route('teacher.modules.show', $module) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Clear Filters
                            </a>
                            @if($search)
                                <span class="badge bg-info ms-2">Search: {{ $search }}</span>
                            @endif
                            @if($statusFilter !== 'all')
                                <span class="badge bg-info ms-2">Status: {{ ucfirst($statusFilter) }}</span>
                            @endif
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Currently Enrolled Students --}}
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-people"></i> Currently Enrolled Students
                    <span class="badge bg-light text-dark ms-2">{{ $activeStudents->count() }}</span>
                </h5>
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeStudents as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->user->name }}</td>
                                    <td>{{ $enrollment->user->email }}</td>
                                    <td>{{ $enrollment->enrolled_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-primary">Enrolled</span>
                                    </td>
                                    <td>
                                        <button type="button" 
                                                class="btn btn-sm btn-success" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#gradeModal{{ $enrollment->id }}">
                                            <i class="bi bi-check-circle"></i> Set Grade
                                        </button>
                                    </td>
                                </tr>

                                <!-- Grade Modal -->
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
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No students currently enrolled in this module.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($activeStudents->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $activeStudents->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Graded Students --}}
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-clipboard-check"></i> Graded Students
                    <span class="badge bg-light text-dark ms-2">{{ $gradedStudents->count() }}</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Enrolled Date</th>
                                <th>Completed Date</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($gradedStudents as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->user->name }}</td>
                                    <td>{{ $enrollment->user->email }}</td>
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
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No students have been graded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($gradedStudents->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $gradedStudents->links() }}
                    </div>
                @endif
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
                            <h3>{{ $activeStudents->count() }}</h3>
                            <p class="mb-0">Currently Enrolled</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-success text-white rounded">
                            <h3>{{ $gradedStudents->where('status', 'pass')->count() }}</h3>
                            <p class="mb-0">Passed</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-danger text-white rounded">
                            <h3>{{ $gradedStudents->where('status', 'fail')->count() }}</h3>
                            <p class="mb-0">Failed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection