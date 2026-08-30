@extends('welcome')

@section('title', 'Edit')

@section('content')

  <div class="container-fluid px-0 mb-5">

    <!-- Cover / banner -->
    <div class="profile-cover d-flex align-items-end justify-content-center position-relative">
    </div>

    <div class="container" style="max-width: 700px;">
      <div class="text-center position-relative" style="margin-top: -70px;">

        <!-- Avatar with upload overlay -->
        <div class="avatar-wrapper mx-auto">
          <img
            src="{{ $user->photo ? asset('images/profile/' . $user->photo) : asset('images/default-avatar.jpg') }}"
            alt="profile photo" id="avatarPreview" class="avatar-img">

          <label for="photoInput" class="avatar-edit-btn" title="Change photo">
            <i class="fa-solid fa-camera"></i>
          </label>
        </div>

        <h4 class="mt-3 mb-0">{{ $user->name }}</h4>
        <p class="text-muted mb-4">{{ $user->status ?? 'No status set' }}</p>
      </div>

      <div class="card shadow-sm">
        <div class="card-body p-4">

          <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <!-- Hidden file input, triggered by the camera icon on the avatar -->
            <input type="file" name="photo" id="photoInput" accept="image/*" class="d-none">
            @error('photo')
              <div class="text-danger small mb-3">{{ $message }}</div>
            @enderror

            <!-- Status -->
            <div class="mb-3">
              <label for="status" class="form-label">Status</label>
              <input type="text" name="status" id="status"
                class="form-control @error('status') is-invalid @enderror"
                placeholder="What's on your mind?" maxlength="150"
                value="{{ old('status', $user->status) }}">
              @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Name -->
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $user->name) }}" required>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Email -->
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" name="email" id="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $user->email) }}" required>
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <hr class="my-4">

            <p class="text-muted small mb-3">Leave password fields blank if you don't want to change it.</p>

            <!-- New Password -->
            <div class="mb-3">
              <label for="password" class="form-label">New Password</label>
              <input type="password" name="password" id="password"
                class="form-control @error('password') is-invalid @enderror">
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Confirm Password  -->
            <div class="mb-4"> 
              <label for="password_confirmation" class="form-label">Confirm New Password</label>
              <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            </div>

            <div class="d-flex justify-content-end">
              <button type="submit" class="btn btn-primary rounded-2">
                <i class="fa-solid fa-floppy-disk me-1"></i> Save Changes
              </button>
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>

@endsection

@section('scripts')
  <style>
    .profile-cover {
      height: 220px;
      width: 100%;
      background: linear-gradient(135deg, #4b6cb7, #182848);
    }

    .avatar-wrapper {
      position: relative;
      width: 140px;
      height: 140px;
    }

    .avatar-img {
      width: 140px;
      height: 140px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #fff;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
      background-color: #fff;
    }

    .avatar-edit-btn {
      position: absolute;
      bottom: 6px;
      right: 6px;
      width: 34px;
      height: 34px;
      background-color: #1e90ff;
      color: #fff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      border: 2px solid #fff;
      font-size: 14px;
      transition: background-color 0.2s ease;
    }

    .avatar-edit-btn:hover {
      background-color: #0d6efd;
    }
  </style>

  <script>
    // camera icon နှိပ်ရင် hidden file input ကို trigger လုပ်မယ်
    document.getElementById('photoInput').addEventListener('change', function (e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (evt) {
          document.getElementById('avatarPreview').src = evt.target.result;
        };
        reader.readAsDataURL(file);
      }
    });
  </script>
@endsection