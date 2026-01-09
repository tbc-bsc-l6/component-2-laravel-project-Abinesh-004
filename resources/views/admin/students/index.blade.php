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
        <!-- Filter Panel -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-funnel"></i> Filters & Search
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.students.index') }}" method="GET">
                    <div class="row g-3">
                        <!-- Search -->
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" 
                                   name="search" 
                                   id="search"
                                   class="form-control" 
                                   placeholder="Name, email, or role..." 
                                   value="{{ request('search') }}">
                        </div>

                        <!-- Role Filter -->
                        <div class="col-md-2">
                            <label for="role_filter" class="form-label">Role</label>
                            <select name="role_filter" id="role_filter" class="form-select">
                                <option value="">All Roles</option>
                                <option value="student" {{ request('role_filter') == 'student' ? 'selected' : '' }}>Student</option>
                                <option value="old_student" {{ request('role_filter') == 'old_student' ? 'selected' : '' }}>Old Student</option>
                            </select>
                        </div>

                        <!-- Enrollment Filter -->
                        <div class="col-md-2">
                            <label for="enrollment_filter" class="form-label">Enrollment</label>
                            <select name="enrollment_filter" id="enrollment_filter" class="form-select">
                                <option value="">All</option>
                                <option value="enrolled" {{ request('enrollment_filter') == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                                <option value="not_enrolled" {{ request('enrollment_filter') == 'not_enrolled' ? 'selected' : '' }}>Not Enrolled</option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div class="col-md-3">
                            <label for="sort_by" class="form-label">Sort By</label>
                            <select name="sort_by" id="sort_by" class="form-select">
                                <option value="name" {{ request('sort_by', 'name') == 'name' ? 'selected' : '' }}>Name</option>
                                <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
                                <option value="role" {{ request('sort_by') == 'role' ? 'selected' : '' }}>Role</option>
                                <option value="enrollment_count" {{ request('sort_by') == 'enrollment_count' ? 'selected' : '' }}>Enrollment Count</option>
                            </select>
                        </div>

                        <!-- Sort Order -->
                        <div class="col-md-2">
                            <label for="sort_order" class="form-label">Order</label>
                            <select name="sort_order" id="sort_order" class="form-select">
                                <option value="asc" {{ request('sort_order', 'asc') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Apply Filters
                            </button>
                            @if(request()->hasAny(['search', 'role_filter', 'enrollment_filter', 'sort_by', 'sort_order']))
                                <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Clear All
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Active Filters Display -->
        @if(request()->hasAny(['search', 'role_filter', 'enrollment_filter']))
            <div class="mb-3">
                <strong>Active Filters:</strong>
                @if(request('search'))
                    <span class="badge bg-info">
                        Search: {{ request('search') }}
                        <a href="{{ route('admin.students.index', array_diff_key(request()->all(), ['search' => ''])) }}" 
                           class="text-white text-decoration-none ms-1">×</a>
                    </span>
                @endif
                @if(request('role_filter'))
                    <span class="badge bg-info">
                        Role: {{ ucfirst(str_replace('_', ' ', request('role_filter'))) }}
                        <a href="{{ route('admin.students.index', array_diff_key(request()->all(), ['role_filter' => ''])) }}" 
                           class="text-white text-decoration-none ms-1">×</a>
                    </span>
                @endif
                @if(request('enrollment_filter'))
                    <span class="badge bg-info">
                        Enrollment: {{ ucfirst(str_replace('_', ' ', request('enrollment_filter'))) }}
                        <a href="{{ route('admin.students.index', array_diff_key(request()->all(), ['enrollment_filter' => ''])) }}" 
                           class="text-white text-decoration-none ms-1">×</a>
                    </span>
                @endif
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>
                            Name
                            @if(request('sort_by', 'name') == 'name')
                                <i class="bi bi-sort-{{ request('sort_order', 'asc') == 'asc' ? 'down' : 'up' }}"></i>
                            @endif
                        </th>
                        <th>
                            Email
                            @if(request('sort_by') == 'email')
                                <i class="bi bi-sort-{{ request('sort_order', 'asc') == 'asc' ? 'down' : 'up' }}"></i>
                            @endif
                        </th>
                        <th>
                            Role
                            @if(request('sort_by') == 'role')
                                <i class="bi bi-sort-{{ request('sort_order', 'asc') == 'asc' ? 'down' : 'up' }}"></i>
                            @endif
                        </th>
                        <th>
                            Active Enrollments
                            @if(request('sort_by') == 'enrollment_count')
                                <i class="bi bi-sort-{{ request('sort_order', 'asc') == 'asc' ? 'down' : 'up' }}"></i>
                            @endif
                        </th>
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