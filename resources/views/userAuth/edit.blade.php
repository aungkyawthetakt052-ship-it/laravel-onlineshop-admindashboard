@extends('adminview.dashboard')

@section('title', 'Edit User')

@section('content')
    <div class="container-fluid" style="max-width: 560px;">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1 fw-semibold">Edit User</h4>
                <p class="text-muted small mb-0">Update user information</p>
            </div>
            <a href="{{ route('users') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                <form action="{{ route('useredit', $user->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="name" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                            class="form-control @error('name') is-invalid @enderror" placeholder="Enter username">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="form-control @error('email') is-invalid @enderror" placeholder="name@example.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="user_type" class="form-label">User Type <span class="text-danger">*</span></label>
                        <select name="user_type" id="user_type" class="form-select">
                            <option value="user" {{ old('user_type', $user->user_type) == 'user' ? 'selected' : '' }}>User
                            </option>
                            @if(Auth::user()->isSuperAdmin())
                                <option value="admin" {{ old('user_type', $user->user_type) == 'admin' ? 'selected' : '' }}>Admin
                                </option>
                                <option value="superadmin" {{ old('user_type', $user->user_type) == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                            @endif
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('users') }}" class="btn btn-outline-secondary w-50">Cancel</a>
                        <button type="submit" class="btn btn-primary w-50">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Update
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <style>
        /* ===== No blue focus border ===== */
        .form-control:focus,
        .form-select:focus {
            border-color: #ced4da !important;
            box-shadow: none !important;
            outline: none !important;
        }

        .form-control.is-invalid:focus {
            border-color: #dc3545 !important;
            box-shadow: none !important;
        }

        [data-bs-theme="dark"] .form-control:focus,
        [data-bs-theme="dark"] .form-select:focus {
            border-color: #495057 !important;
            box-shadow: none !important;
        }
    </style>
@endsection