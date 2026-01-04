{{-- resources/views/admin/modules/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Module')
@section('page-title', 'Create New Module')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Add New Module</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.modules.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="module" class="form-label">Module Name</label>
                        <input type="text" 
                               class="form-control @error('module') is-invalid @enderror" 
                               id="module" 
                               name="module" 
                               value="{{ old('module') }}"
                               required>
                        @error('module')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.modules.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Module</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection