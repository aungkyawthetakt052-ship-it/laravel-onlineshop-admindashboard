@extends('adminview.dashboard')

@section('title', 'User Management')

@section('content')

  {{-- Success Alert --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow" id="successAlert"
      role="alert" style="z-index: 1055;">
      <strong>{{ session('success') }}</strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <script>
      setTimeout(() => {
        const el = document.getElementById('successAlert');
        if (el) {
          el.classList.remove('show');
          setTimeout(() => el.remove(), 300);
        }
      }, 3000);
    </script>
  @endif

  {{--========= Header + Search =======--}}
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

    <!-- Title -->
    <div>
      <h4 class="mb-1 fw-semibold">User Management</h4>
      <p class="text-muted small mb-0">Manage all users and their roles</p>
    </div>

    <!-- Search + Create Button (ညာဘက်) -->
    <div class="d-flex gap-2 ms-auto flex-wrap">

      <!-- Beautiful Search Box -->
      <form action="{{ route('users') }}" method="GET">
        <div class="input-group shadow-sm rounded-pill overflow-hidden border" style="width: 280px;">

          <span class="input-group-text bg-transparent border-0 ps-3">
            <i class="fa-solid fa-magnifying-glass text-muted"></i>
          </span>

          <input type="text" name="search" class="form-control border-0 shadow-none" placeholder="Search name or email..."
            value="{{ request('search') }}" style="background: transparent;">

          @if(request('search'))
            <a href="{{ route('users') }}" class="btn btn-link text-danger px-2" title="Clear">
              <i class="fa-solid fa-xmark"></i>
            </a>
          @endif

          <button type="submit" class="btn btn-primary px-3 rounded-0">
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </div>
      </form>

      <!-- Create User Button -->
      <a href="{{ route('createpage') }}" class="btn btn-primary text-nowrap d-flex align-items-center">
        <i class="fa-solid fa-plus me-1"></i> Create User
      </a>

    </div>
  </div>
  {{--========= End Header + Search =======--}}


  {{--======== Users Table =========--}}
  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th class="ps-3">ID</th>
            <th>User</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Created</th>
            <th class="text-end pe-3">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $user)
            <tr>
              <td class="ps-3 text-muted">#{{ $user->id }}</td>

              {{-- ======User (Avatar + Name)===== --}}
              <td>
                <div class="d-flex align-items-center gap-2">
                  <div class="user-avatar">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                  </div>
                  <span class="fw-medium">{{ $user->name }}</span>
                </div>
              </td>
              {{-- ====== End User (Avatar + Name)===== --}}

              <td>{{ $user->email }}</td>

              {{--======= User Type =======--}}
              <td>
                @if($user->user_type === 'superadmin')
                  <span class="badge rounded-pill px-3 py-1"
                    style="background: linear-gradient(135deg, #F5B700, #D99A00); color: #0B1220;">
                    Super Admin
                  </span>
                @elseif($user->user_type === 'admin')
                  <span class="badge rounded-pill bg-primary px-3 py-1">Admin</span>
                @else
                  <span class="badge rounded-pill bg-secondary px-3 py-1">User</span>
                @endif
              </td>
              {{--======= End User Type =======--}}


              {{--======= Status ======= --}}
              <td>
                @if($user->is_active == 1)
                  <span class="badge rounded-pill bg-success px-3 py-1">Active</span>
                @else
                  <span class="badge rounded-pill bg-danger px-3 py-1">Disable</span>
                @endif
              </td>
              {{--======= End Status ======= --}}

              {{--======= Created =======--}}
              <td class="text-muted small">
                {{ $user->created_at?->format('d M Y') }}
              </td>
              {{--======= End Created =======--}}

              {{--====== Action =======--}}
              <td class="text-end">
                @if($user->canBeManagedBy(Auth::user()))
                  {{-- Manage လုပ်ခွင့်ရှိမှ Dropdown ကို ပြမယ် --}}
                  <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown"
                      style="width: 36px; height: 36px; border-radius: 8px; padding: 0;">
                      ⋮
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                      <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-3"
                          href="{{ route('userdetailpage', $user->id) }}">
                          <span>👁️</span> View
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-3"
                          href="{{ route('editpage', $user->id) }}">
                          <span>✏️</span> Edit
                        </a>
                      </li>
                      <li>
                        <form action="{{ route('userstatus', $user->id) }}" method="POST">
                          @csrf
                          @method('PATCH')
                          <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 px-3"
                            style="width:100%; border:none; background:transparent;">
                            @if($user->is_active == 1)
                              <span>🚫</span> Disable
                            @else
                              <span>✅</span> Activate
                            @endif
                          </button>
                        </form>
                      </li>
                      <li>
                        <form action="{{ route('userdelete', $user->id) }}" method="POST" class="delete-form">
                          @csrf
                          @method('DELETE')
                          <button type="submit"
                            class="dropdown-item d-flex align-items-center gap-2 py-2 px-3 text-danger delete-btn"
                            style="width:100%; border:none; background:transparent;">
                            <span>🗑️</span> Delete
                          </button>
                        </form>
                      </li>
                    </ul>
                  </div>
                @else
                  {{-- Manage ခွင့်မရှိရင် View ကိုပဲ ပြမယ်၊ ဒါမှမဟုတ် လုံးဝ ဖျောက်လည်းရတယ် --}}
                  <a href="{{ route('userdetailpage', $user->id) }}" class="btn btn-sm btn-outline-secondary"
                    title="View only">
                    👁️
                  </a>
                @endif
              </td>
              {{--====== End Action =======--}}

            </tr>
          @empty
            <tr>
              <!-- ===== Error search name -->
              <td colspan="7" class="text-center text-muted py-5">
                <i class="fa-solid fa-users fa-2x mb-2 d-block opacity-50"></i>
                No users found
              </td>
              <!-- =====  End Error search name -->

            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

@endsection

@section('scripts')
  <style>
    .user-avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: linear-gradient(135deg, #0d6efd, #6610f2);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.85rem;
      font-weight: 600;
      flex-shrink: 0;
    }

    .table th {
      font-weight: 600;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.03em;
      color: var(--bs-secondary-color);
    }

    .bg-primary-subtle {
      background-color: rgba(13, 110, 253, 0.15) !important;
    }

    .bg-success-subtle {
      background-color: rgba(25, 135, 84, 0.15) !important;
    }

    .bg-danger-subtle {
      background-color: rgba(220, 53, 69, 0.15) !important;
    }

    .bg-secondary-subtle {
      background-color: rgba(108, 117, 125, 0.15) !important;
    }

    .input-group .btn-outline-secondary:hover {
      background-color: #dc3545;
      border-color: #dc3545;
      color: #fff;
    }

    /* Table header - theme aware */
    .table thead th {
      font-weight: 600;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.03em;
      background-color: var(--bs-tertiary-bg) !important;
      color: var(--bs-secondary-color) !important;
      border-bottom: 1px solid var(--bs-border-color) !important;
    }

    /* Dark mode မှာ ပိုသေသပ်အောင် */
    [data-bs-theme="dark"] .table thead th {
      background-color: #2a2a3d !important;
      color: #adb5bd !important;
    }

    [data-bs-theme="light"] .table thead th {
      background-color: #f8f9fa !important;
      color: #6c757d !important;
    }

    [data-bs-theme="dark"] .table thead th .btnDropdown {
      background-color: #2a2a3d !important;
      color: #adb5bd !important;
    }

    [data-bs-theme="light"] .table thead th .btnDropdown {
      background-color: #f8f9fa !important;
      color: #6c757d !important;
    }

    /* Beautiful Search Box */
    /* .input-group.rounded-pill {
      transition: box-shadow 0.2s ease;
    }

    .input-group.rounded-pill:focus-within {
      box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15) !important;
      border-color: #86b7fe !important;
    }

    .input-group .form-control:focus {
      box-shadow: none !important;
      background: transparent !important;
    } */

    /* Mobile မှာ full width */
    @media (max-width: 575.98px) {
      .input-group {
        width: 100% !important;
      }
    }
    /* End searchbox */
  </style>
@endsection