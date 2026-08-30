@extends('welcome')

@section('title', 'Admin Login')

@section('content')
  <div class="container">
    <form class="col-md-5 col-lg-4 mx-auto mt-5 p-4 card shadow-sm login-card" action="{{ route('admin.login') }}"
      method="post">
      @csrf

      <h2 class="text-center mb-4">Admin Login</h2>

      {{-- Email --}}
      <div class="mb-3">
        <label for="email" class="form-label">Email address <span class="text-danger">*</span></label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email"
          value="{{ old('email') }}" placeholder="Enter your email" autofocus>
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- Password --}}
      <div class="mb-3">
        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
        <div class="position-relative">
          <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
            id="password" placeholder="Enter your password" style="padding-right: 45px;">

          <button type="button" id="togglePassword"
            class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-0 border-0 bg-transparent"
            style="display: none;">
            <i class="fa-solid fa-eye" id="eyeIcon"></i>
          </button>
        </div>
        @error('password')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
    </form>
  </div>
@endsection

@section('scripts')
  <style>
    .login-card {
      background-color: var(--bs-body-bg);
      border: 1px solid var(--bs-border-color);
    }

    h2 {
      text-decoration: underline;
      text-underline-offset: 8px;
    }

    #togglePassword {
      z-index: 5;
      cursor: pointer;
    }

    #togglePassword i {
      color: var(--bs-secondary-color);
    }

    #togglePassword:hover i {
      color: var(--bs-body-color);
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const passwordInput = document.getElementById('password');
      const togglePassword = document.getElementById('togglePassword');
      const eyeIcon = document.getElementById('eyeIcon');

      passwordInput.addEventListener('input', function () {
        togglePassword.style.display = this.value.length > 0 ? 'block' : 'none';
      });

      togglePassword.addEventListener('click', function () {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        eyeIcon.classList.toggle('fa-eye', !isPassword);
        eyeIcon.classList.toggle('fa-eye-slash', isPassword);
      });
    });
  </script>
@endsection