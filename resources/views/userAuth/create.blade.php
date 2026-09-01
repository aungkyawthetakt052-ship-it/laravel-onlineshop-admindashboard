@extends('adminview.dashboard')

@section('title', 'Create User')

@section('content')

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-semibold">Create User</h4>
            <p class="text-muted small mb-0">Add a new user to the system</p>
        </div>
        <a href="{{ route('users') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back
        </a>
    </div>

    {{-- Form Card --}}
    <div class="card border-0 shadow-sm" style="max-width: 520px;">
        <div class="card-body p-4">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('user.create') }}" method="POST">
                @csrf

                {{-- Username --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="form-control @error('name') is-invalid @enderror" placeholder="Enter username">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror" placeholder="name@example.com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- User Type --}}
                <div class="mb-3">
                    <label for="user_type" class="form-label">User Type <span class="text-danger">*</span></label>
                    <select name="user_type" id="user_type" class="form-select @error('user_type') is-invalid @enderror"
                        required>
                        <option value="user" {{ old('user_type') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ old('user_type') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('user_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <input type="password" name="password" id="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="Enter password"
                            style="padding-right: 42px;">

                        <button type="button" id="togglePassword"
                            class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-0 border-0 bg-transparent"
                            style="display: none;">
                            <i class="fa-solid fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="d-flex gap-2">
                    <a href="{{ route('users') }}" class="btn btn-outline-secondary w-50">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary w-50">
                        <i class="fa-solid fa-user-plus me-1"></i> Create User
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <style>
        #togglePassword {
            z-index: 5;
            cursor: pointer;
        }

        #togglePassword i {
            color: var(--bs-secondary-color, #6c757d);
        }

        #togglePassword:hover i {
            color: var(--bs-body-color);
        }

        /* ===== Edge / Chrome / Safari Native Password Icon ဖျောက် ===== */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }

        input[type="password"]::-webkit-credentials-auto-fill-button,
        input[type="password"]::-webkit-textfield-decoration-container {
            display: none !important;
            visibility: hidden !important;
            pointer-events: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* ===== Firefox password appearance ===== */
        input[type="password"] {
            -moz-appearance: textfield;
            appearance: textfield;
            /* ← Standard property */
        }

        /* form border color hide  */
        .form-control:focus,
        .form-select:focus {
            border-color: #ced4da !important;
            /* မူလမီးခိုးရောင် */
            box-shadow: none !important;
            /* အပြာရောင် glow ဖယ် */
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');
            const eyeIcon = document.getElementById('eyeIcon');

            // element ရှိမှသာ အလုပ်လုပ်မယ် (error မတက်အောင်)
            if (passwordInput && togglePassword && eyeIcon) {

                // စာရိုက်တိုင်း icon ပြ/ဖျောက်
                passwordInput.addEventListener('input', function () {
                    togglePassword.style.display = passwordInput.value.length > 0 ? 'block' : 'none';
                });

                // Eye icon နှိပ်ရင် show/hide
                togglePassword.addEventListener('click', function () {
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
                    }
                });
            }
        });
    </script>
@endsection