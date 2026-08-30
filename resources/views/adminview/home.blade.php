@extends('adminview.dashboard')

@section('title', 'Admin Dashboard')

@section('content')

  <div class="container-fluid">

    <!-- Welcome Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
      <div>
        <h3 class="mb-1 fw-semibold">Welcome back, {{ Auth::user()->name }} 👋</h3>
        <p class="text-muted mb-0">Here's what's happening with your store today.</p>
      </div>
      <div class="d-flex align-items-center gap-2">
        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
          <i class="fa-solid fa-shield-halved me-1"></i> Admin
        </span>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">

      <!-- Total Users -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon bg-primary-subtle text-primary">
              <i class="fa-solid fa-users"></i>
            </div>
            <div>
              <div class="text-muted small">Total Users</div>
              <div class="fs-4 fw-bold">{{ $totalUsers ?? 0 }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Products -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon bg-success-subtle text-success">
              <i class="fa-solid fa-box"></i>
            </div>
            <div>
              <div class="text-muted small">Total Products</div>
              <div class="fs-4 fw-bold">{{ $totalProducts ?? 0 }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Orders (placeholder) -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon bg-warning-subtle text-warning">
              <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <div>
              <div class="text-muted small">Total Orders</div>
              <div class="fs-4 fw-bold">{{ $totalOrders ?? 0 }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Admins -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon bg-info-subtle text-info">
              <i class="fa-solid fa-user-shield"></i>
            </div>
            <div>
              <div class="text-muted small">Admins</div>
              <div class="fs-4 fw-bold">{{ $totalAdmins ?? 0 }}</div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- Quick Actions + Recent -->
    <div class="row g-3">

      <!-- Quick Actions -->
      <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-transparent border-0 pt-3">
            <h5 class="mb-0 fw-semibold">Quick Actions</h5>
          </div>
          <div class="card-body d-grid gap-2">
            <a href="{{ route('admin.productcreatepage') }}" class="btn btn-outline-primary text-start">
              <i class="fa-solid fa-plus me-2"></i> Add New Product
            </a>
            <a href="{{ route('createpage') }}" class="btn btn-outline-success text-start">
              <i class="fa-solid fa-user-plus me-2"></i> Add New User
            </a>
            <a href="{{ route('admin.productpage') }}" class="btn btn-outline-secondary text-start">
              <i class="fa-solid fa-box me-2"></i> Manage Products
            </a>
            <a href="{{ route('users') }}" class="btn btn-outline-secondary text-start">
              <i class="fa-solid fa-users me-2"></i> Manage Users
            </a>
            <a href="{{ route('admin.orderpage') }}" class="btn btn-outline-secondary text-start">
              <i class="fa-solid fa-users me-2"></i> Manage Orders
            </a>
          </div>
        </div>
      </div>

      <!-- Admin Profile Card -->
      <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-transparent border-0 pt-3">
            <h5 class="mb-0 fw-semibold">Your Profile</h5>
          </div>
          <div class="card-body">
            <div class="d-flex align-items-center gap-3 mb-4">
              <div class="avatar-circle">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
              </div>
              <div>
                <h5 class="mb-0">{{ Auth::user()->name }}</h5>
                <div class="text-muted small">{{ Auth::user()->email }}</div>
                <span class="badge bg-primary-subtle text-primary mt-1">Admin</span>
              </div>
            </div>

            <div class="row g-3">
              <div class="col-sm-6">
                <div class="p-3 rounded bg-body-secondary">
                  <div class="text-muted small">Account Status</div>
                  <div class="fw-semibold text-success">
                    <i class="fa-solid fa-circle-check me-1"></i> Active
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="p-3 rounded bg-body-secondary">
                  <div class="text-muted small">Role</div>
                  <div class="fw-semibold">Administrator</div>
                </div>
              </div>
            </div>

            <div class="mt-4">
              <a href="{{ route('admin.logout') }}" class="btn btn-outline-danger btn-sm">
                <i class="fa-solid fa-right-from-bracket me-1"></i> Sign out
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

@endsection

@section('scripts')
  <style>
    .stat-icon {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
      flex-shrink: 0;
    }

    .avatar-circle {
      width: 56px;
      height: 56px;
      border-radius: 50%;
      background: linear-gradient(135deg, #0d6efd, #6610f2);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
      font-weight: 600;
      flex-shrink: 0;
    }

    .card {
      border-radius: 12px;
    }

    .bg-primary-subtle {
      background-color: rgba(13, 110, 253, 0.15) !important;
    }

    .bg-success-subtle {
      background-color: rgba(25, 135, 84, 0.15) !important;
    }

    .bg-warning-subtle {
      background-color: rgba(255, 193, 7, 0.15) !important;
    }

    .bg-info-subtle {
      background-color: rgba(13, 202, 240, 0.15) !important;
    }

    .text-primary {
      color: #0d6efd !important;
    }

    .text-success {
      color: #198754 !important;
    }

    .text-warning {
      color: #ffc107 !important;
    }

    .text-info {
      color: #0dcaf0 !important;
    }
  </style>
@endsection