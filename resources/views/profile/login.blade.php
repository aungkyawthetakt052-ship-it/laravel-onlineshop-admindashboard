@extends('welcome')

@section('title', 'Login')

@section('content')
<div class="container">
  <form class="col-md-5 col-lg-4 mx-auto mt-5 p-4 card shadow-sm login-card" 
        action="{{ route('login.store') }}" method="post">
    @csrf

    <h2 class="text-center mb-4">Login</h2>

    {{-- Email --}}
    <div class="mb-3">
      <label for="email" class="form-label">Email address <span class="text-danger">*</span></label>
      <input type="email" 
             class="form-control @error('email') is-invalid @enderror" 
             name="email" 
             id="email" 
             value="{{ old('email') }}" 
             placeholder="Enter your email"
             autofocus>
      @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <div class="form-text">We'll never share your email with anyone else.</div>
    </div>

    {{-- Password --}}
    <div class="mb-3">
      <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
      <div class="position-relative">
        <input type="password" 
               class="form-control @error('password') is-invalid @enderror" 
               name="password" 
               id="password" 
               placeholder="Enter your password"
               style="padding-right: 45px;">
        
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

    {{-- Remember me --}}
    <div class="mb-3 form-check">
      <input type="checkbox" name="remember" class="form-check-input" id="remember">
      <label class="form-check-label" for="remember">Remember me</label>
    </div>

    <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
  </form>

  <div class="col-md-5 col-lg-4 mx-auto text-center mt-3">
    <p>Don't have an account? 
      <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">Register</a>
    </p>
  </div>
</div>
@endsection

@section('scripts')
<style>
  /* Theme-aware card */
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
  const passwordInput = document.getElementById('password');
  const togglePassword = document.getElementById('togglePassword');
  const eyeIcon = document.getElementById('eyeIcon');

  // Password ရိုက်ရင် eye icon ပေါ်အောင်
  passwordInput.addEventListener('input', function () {
    togglePassword.style.display = this.value.length > 0 ? 'block' : 'none';
  });

  // Show / Hide password
  togglePassword.addEventListener('click', function () {
    const isPassword = passwordInput.type === 'password';
    passwordInput.type = isPassword ? 'text' : 'password';
    
    eyeIcon.classList.toggle('fa-eye', !isPassword);
    eyeIcon.classList.toggle('fa-eye-slash', isPassword);
  });
</script>
@endsection