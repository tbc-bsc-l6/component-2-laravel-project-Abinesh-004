{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Profile')
@section('page-title', 'Edit Profile')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <!-- Profile Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Profile Information</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Update your account's profile information and email address.</p>
                
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               required 
                               autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="alert alert-warning mt-2">
                                <p class="mb-2">Your email address is unverified.</p>
                                <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning">
                                        Click here to re-send the verification email
                                    </button>
                                </form>
                            </div>

                            @if (session('status') === 'verification-link-sent')
                                <div class="alert alert-success mt-2">
                                    A new verification link has been sent to your email address.
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Role (Read-only) -->
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <input type="text" 
                               class="form-control" 
                               id="role" 
                               value="{{ ucfirst(str_replace('_', ' ', $user->role->role)) }}" 
                               readonly>
                        <small class="text-muted">Your role cannot be changed from this page.</small>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Save Changes
                        </button>

                        @if (session('status') === 'profile-updated')
                            <span class="text-success">
                                <i class="bi bi-check-circle"></i> Saved successfully!
                            </span>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Update Password -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Update Password</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Ensure your account is using a long, random password to stay secure.</p>
                
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" 
                               class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                               id="current_password" 
                               name="current_password" 
                               autocomplete="current-password">
                        @error('current_password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" 
                               class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               autocomplete="new-password">
                        @error('password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" 
                               class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               autocomplete="new-password">
                        @error('password_confirmation', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-key"></i> Update Password
                        </button>

                        @if (session('status') === 'password-updated')
                            <span class="text-success">
                                <i class="bi bi-check-circle"></i> Password updated successfully!
                            </span>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
