{{-- resources/views/admin/students/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Manage Students')
@section('page-title', 'Manage Students')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Students</h5>
    </div>
    <div class="card-body">
        <!-- Search Form -->
        <form action="{{ route('admin.students.index') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       placeholder="Search by name, email, or role..." 
                       value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-search"></i> Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Clear
                    </a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Active Enrollments</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->email }}</td>
                            <td>
                                <span class="badge bg-{{ $student->role->role === 'student' ? 'primary' : 'secondary' }}">
                                    {{ ucfirst($student->role->role) }}
                                </span>
                            </td>
                            <td>
                                @if($student->activeEnrollments->count() > 0)
                                    @foreach($student->activeEnrollments as $enrollment)
                                        <span class="badge bg-info">{{ $enrollment->module->module }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">None</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#roleModal{{ $student->id }}">
                                    <i class="bi bi-pencil"></i> Change Role
                                </button>
                            </td>
                        </tr>

                        <!-- Change Role Modal -->
                        <div class="modal fade" id="roleModal{{ $student->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Change Role for {{ $student->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.students.change-role', $student) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="role" class="form-label">Select New Role</label>
                                                <select class="form-select" name="role" required>
                                                    <option value="student" {{ $student->role->role === 'student' ? 'selected' : '' }}>Student</option>
                                                    <option value="old_student" {{ $student->role->role === 'old_student' ? 'selected' : '' }}>Old Student</option>
                                                    <option value="teacher" {{ $student->role->role === 'teacher' ? 'selected' : '' }}>Teacher</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Update Role</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No students found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection