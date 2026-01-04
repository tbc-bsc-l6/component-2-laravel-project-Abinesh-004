{{-- resources/views/admin/teachers/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Manage Teachers')
@section('page-title', 'Manage Teachers')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Teachers</h5>
        <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Teacher
        </a>
    </div>
    <div class="card-body">
        <!-- Search Form -->
        <form action="{{ route('admin.teachers.index') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       placeholder="Search by name or email..." 
                       value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-search"></i> Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">
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
                        <th>Assigned Modules</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr>
                            <td>{{ $teacher->id }}</td>
                            <td>{{ $teacher->name }}</td>
                            <td>{{ $teacher->email }}</td>
                            <td>
                                @if($teacher->teacherModules->count() > 0)
                                    @foreach($teacher->teacherModules as $tm)
                                        <span class="badge bg-info">{{ $tm->module->module }}</span>
                                        <form action="{{ route('admin.teachers.detach-module') }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
                                            <input type="hidden" name="module_id" value="{{ $tm->module->id }}">
                                            <button type="submit" class="btn btn-sm btn-link text-danger p-0">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </form>
                                    @endforeach
                                @else
                                    <span class="text-muted">None</span>
                                @endif
                                
                                <!-- Assign Module Form -->
                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#assignModal{{ $teacher->id }}">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </td>
                            <td>
                                <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to remove this teacher?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Assign Module Modal -->
                        <div class="modal fade" id="assignModal{{ $teacher->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Assign Module to {{ $teacher->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.teachers.attach-module') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
                                            <div class="mb-3">
                                                <label for="module_id" class="form-label">Select Module</label>
                                                <select class="form-select" name="module_id" required>
                                                    <option value="">Choose...</option>
                                                    @foreach($modules as $module)
                                                        <option value="{{ $module->id }}">{{ $module->module }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Assign Module</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No teachers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection