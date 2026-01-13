{{-- resources/views/admin/modules/show.blade.php --}}
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
                            {{ $module->active_students_count }} active students
                        </p>
                    </div>
                    <a href="{{ route('admin.modules.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Modules
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
                                        <form action="{{ route('admin.modules.remove-student', $module) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to remove this student?');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="user_id" value="{{ $enrollment->user->id }}">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i> Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No students enrolled in this module.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
