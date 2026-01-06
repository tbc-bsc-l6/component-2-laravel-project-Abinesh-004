{{-- resources/views/student/enroll.blade.php --}}
@extends('layouts.app')

@section('title', 'Enroll in Modules')
@section('page-title', 'Enroll in Modules')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Enrollment Status</h5>
                <p class="card-text">
                    You are currently enrolled in <strong>{{ $activeCount }}/4</strong> modules.
                </p>
                @if(!$canEnroll)
                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-exclamation-triangle"></i>
                        You have reached the maximum of 4 active modules. 
                        Please complete a module before enrolling in new ones.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($canEnroll)
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Available Modules</h5>
                </div>
                <div class="card-body">
                    @if($availableModules->count() > 0)
                        <div class="row g-4">
                            @foreach($availableModules as $module)
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $module->module }}</h5>
                                            <p class="card-text">
                                                <i class="bi bi-people"></i> 
                                                {{ $module->activeStudents()->count() }}/10 students enrolled
                                            </p>
                                            <div class="progress mb-3">
                                                @php
                                                    $enrolled = $module->activeStudents()->count();
                                                    $percentage = ($enrolled / 10) * 100;
                                                @endphp
                                                <div class="progress-bar {{ $percentage > 80 ? 'bg-warning' : 'bg-success' }}" 
                                                     role="progressbar" 
                                                     style="width: {{ $percentage }}%">
                                                    {{ $enrolled }} / 10
                                                </div>
                                            </div>
                                            @php
                                                $spotsLeft = 10 - $enrolled;
                                            @endphp
                                            @if($spotsLeft > 0)
                                                <span class="badge bg-success mb-2">
                                                    {{ $spotsLeft }} {{ Str::plural('spot', $spotsLeft) }} available
                                                </span>
                                            @endif
                                            
                                            <form action="{{ route('student.enroll.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="module_id" value="{{ $module->id }}">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="bi bi-plus-circle"></i> Enroll
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-info-circle"></i>
                            No modules are currently available for enrollment. Please check back later.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
@endsection