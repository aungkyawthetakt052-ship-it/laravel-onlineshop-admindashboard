<!-- resources/views/adminview/home.blade.php (NEW FILE) -->
@extends('adminview.dashboard')

@section('title', 'products')

@section('content')
    <!-- Main Content -->
    <div class="flex-grow-1 p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Create User</h2>
            <a href="{{ route('users') }}" class="btn sidebar-link btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="card shadow-sm border-0" style="max-width: 500px;">
            <div class="card-body p-4">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ session('success') }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('user.create') }}" method="post">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror" placeholder="Enter username">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" placeholder="name@example.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="user_type" class="form-label">User Type <span class="text-danger">*</span></label>
                        <select name="user_type" id="user_type" class="form-select" required>
                            <option value="user" {{ old('user_type', $user->user_type ?? '') == 'user' ? 'selected' : '' }}>
                                User
                            </option>
                            <option value="admin" {{ old('user_type', $user->user_type ?? '') == 'admin' ? 'selected' : '' }}>
                                Admin
                            </option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <input type="password" class="form-control myInput @error('password') is-invalid @enderror"
                                name="password" id="password" value="{{ old('password') }}"
                                placeholder="Enter your password">

                            <button type="button" id="togglePassword"
                                class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-0 border-0 bg-transparent">
                                <i class="fa-solid fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('users') }}" class="btn sidebar-link w-50">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary w-50">
                            <i class="fa-solid fa-user-plus me-1"></i> Create User
                        </button>
                    </div>

            </div>

            </form>

        </div>
    </div>

    </div>
    </div>


@endsection
@section('scripts')

    <style>
        #password {
            padding-right: 40px;
            /* icon ရဲ့ နေရာချန်ထားဖို့ */
        }

        #togglePassword {
            z-index: 5;
            cursor: pointer;
            display: none;
            /* default က ဖျောက်ထား */
        }

        #togglePassword i {
            color: var(--bs-secondary-color, #6c757d);
        }

        #togglePassword:hover i {
            color: var(--bs-body-color);
        }
    </style>
    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');

        // Type လုပ်တိုင်း icon ပြ/ဖျောက် စစ်ဆေးမယ်
        passwordInput.addEventListener('input', function () {
            if (passwordInput.value.length > 0) {
                togglePassword.style.display = 'block';
            } else {
                togglePassword.style.display = 'none';
            }
        });

        // Eye icon click - show/hide password text
        togglePassword.addEventListener('click', function () {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    </script>
@endsection