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
        <!-- Filter Panel -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-funnel"></i> Filters & Search
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.teachers.index') }}" method="GET">
                    <div class="row g-3">
                        <!-- Search -->
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" 
                                   name="search" 
                                   id="search"
                                   class="form-control" 
                                   placeholder="Name or email..." 
                                   value="{{ request('search') }}">
                        </div>

                        <!-- Module Filter -->
                        <div class="col-md-3">
                            <label for="module_filter" class="form-label">Module</label>
                            <select name="module_filter" id="module_filter" class="form-select">
                                <option value="">All Modules</option>
                                @foreach($modules as $module)
                                    <option value="{{ $module->id }}" 
                                            {{ request('module_filter') == $module->id ? 'selected' : '' }}>
                                        {{ $module->module }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div class="col-md-3">
                            <label for="sort_by" class="form-label">Sort By</label>
                            <select name="sort_by" id="sort_by" class="form-select">
                                <option value="name" {{ request('sort_by', 'name') == 'name' ? 'selected' : '' }}>Name</option>
                                <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
                                <option value="module_count" {{ request('sort_by') == 'module_count' ? 'selected' : '' }}>Module Count</option>
                            </select>
                        </div>

                        <!-- Sort Order -->
                        <div class="col-md-2">
                            <label for="sort_order" class="form-label">Order</label>
                            <select name="sort_order" id="sort_order" class="form-select">
                                <option value="asc" {{ request('sort_order', 'asc') == 'asc' ? 'selected' : '' }}>
                                    <i class="bi bi-sort-alpha-down"></i> Ascending
                                </option>
                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>
                                    <i class="bi bi-sort-alpha-up"></i> Descending
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Apply Filters
                            </button>
                            @if(request()->hasAny(['search', 'module_filter', 'sort_by', 'sort_order']))
                                <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Clear All
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Active Filters Display -->
        @if(request()->hasAny(['search', 'module_filter']))
            <div class="mb-3">
                <strong>Active Filters:</strong>
                @if(request('search'))
                    <span class="badge bg-info">
                        Search: {{ request('search') }}
                        <a href="{{ route('admin.teachers.index', array_diff_key(request()->all(), ['search' => ''])) }}" 
                           class="text-white text-decoration-none ms-1">×</a>
                    </span>
                @endif
                @if(request('module_filter'))
                    <span class="badge bg-info">
                        Module: {{ $modules->find(request('module_filter'))->module }}
                        <a href="{{ route('admin.teachers.index', array_diff_key(request()->all(), ['module_filter' => ''])) }}" 
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
                            Assigned Modules
                            @if(request('sort_by') == 'module_count')
                                <i class="bi bi-sort-{{ request('sort_order', 'asc') == 'asc' ? 'down' : 'up' }}"></i>
                            @endif
                        </th>
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

        <!-- Pagination Links -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                <p class="text-muted mb-0">
                    Showing {{ $teachers->firstItem() ?? 0 }} to {{ $teachers->lastItem() ?? 0 }} of {{ $teachers->total() }} teachers
                </p>
            </div>
            <div>
                {{ $teachers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection