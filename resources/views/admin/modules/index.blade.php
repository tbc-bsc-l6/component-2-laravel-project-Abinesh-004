{{-- resources/views/admin/modules/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Manage Modules')
@section('page-title', 'Manage Modules')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Modules</h5>
        <a href="{{ route('admin.modules.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Module
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Module Name</th>
                        <th>Status</th>
                        <th>Active Students</th>
                        <th>Available Spots</th>
                        <th>Teachers Assigned</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($modules as $module)
                        <tr>
                            <td>{{ $module->id }}</td>
                            <td>{{ $module->module }}</td>
                            <td>
                                @if($module->is_available)
                                    <span class="badge bg-success">Available</span>
                                @else
                                    <span class="badge bg-secondary">Unavailable</span>
                                @endif
                            </td>
                            <td>{{ $module->active_students_count }}/10</td>
                            <td>
                                @php
                                    $spots = 10 - $module->active_students_count;
                                @endphp
                                <span class="badge {{ $spots > 0 ? 'bg-info' : 'bg-danger' }}">
                                    {{ $spots }} spots
                                </span>
                            </td>
                            <td>{{ $module->teachers_count }}</td>
                            <td>
                                <form action="{{ route('admin.modules.toggle', $module) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-{{ $module->is_available ? 'warning' : 'success' }}">
                                        {{ $module->is_available ? 'Archive' : 'Activate' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No modules found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
