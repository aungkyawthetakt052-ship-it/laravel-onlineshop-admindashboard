@extends('adminview.dashboard')

@section('title', 'Orders')

@section('content')

  {{--===== Success Alert ======--}}
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

  {{--===== Header + Search + Filter =====--}}
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

    <!-- Title -->
    <div>
      <h4 class="mb-1 fw-semibold">Order Management</h4>
      <p class="text-muted small mb-0">Manage all customer orders</p>
    </div>

    <!-- Search + Filter + Add Button -->
    <div class="d-flex gap-2 ms-auto flex-wrap align-items-center">

      <form action="{{ route('admin.orderpage') }}" method="GET" class="d-flex gap-2 flex-wrap align-items-center">

        <!-- Search Box (ပိုကျယ်ထား) -->
        <div class="input-group shadow-sm rounded-pill overflow-hidden border" style="width: 340px;">
          <span class="input-group-text bg-transparent border-0 ps-3">
            <i class="fa-solid fa-magnifying-glass text-muted"></i>
          </span>

          <input type="text" name="search" class="form-control border-0 shadow-none"
            placeholder="Search customer/product..." value="{{ request('search') }}" style="background: transparent;">

          <button type="submit" class="btn btn-primary px-3 rounded-0">
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </div>

        <!-- Status Filter -->
        <select name="status" class="form-select rounded-pill" style="width: 160px;" onchange="this.form.submit()">
          <option value="">All Status</option>
          <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
          <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
          <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>

        <!-- Clear Button -->
        @if(request('search') || request('status'))
          <a href="{{ route('admin.orderpage') }}" class="btn btn-outline-secondary rounded-pill" title="Clear">
            <i class="fa-solid fa-xmark"></i>
          </a>
        @endif
      </form>

      <!-- Add Order Button -->
      <a href="{{ route('admin.ordercreatepage') }}" class="btn btn-primary text-nowrap d-flex align-items-center">
        <i class="fa-solid fa-plus me-1"></i> Add Order
      </a>

    </div>
  </div>
  {{--===== End Header + Search + Filter =====--}}

  {{--===== Summary Cards (Optimized) =====--}}
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card border-0 shadow-sm p-3">
        <small class="text-muted">Total Orders</small>
        <h4 class="fw-bold mb-0">{{ $totalOrders ?? $orders->total() }}</h4>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm p-3">
        <small class="text-muted">Pending</small>
        <h4 class="fw-bold mb-0 text-warning">{{ $statusCounts['pending'] ?? 0 }}</h4>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm p-3">
        <small class="text-muted">Completed</small>
        <h4 class="fw-bold mb-0 text-success">{{ $statusCounts['completed'] ?? 0 }}</h4>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm p-3">
        <small class="text-muted">Cancelled</small>
        <h4 class="fw-bold mb-0 text-danger">{{ $statusCounts['cancelled'] ?? 0 }}</h4>
      </div>
    </div>
  </div>

  {{--===== Orders Table =====--}}
  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th class="ps-3">Order ID</th>
            <th>Customer</th>
            <th>Product</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
            <th class="text-end pe-3">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($orders as $order)
            <tr>
              <td class="ps-3 text-muted">#{{ $order->id }}</td>

              <td>
                <div class="d-flex align-items-center gap-2">
                  <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                    style="width: 36px; height: 36px; font-weight: 600;">
                    {{ strtoupper(substr($order->user->name ?? 'U', 0, 1)) }}
                  </div>
                  <div>
                    <div class="fw-medium">{{ $order->user->name ?? 'Deleted User' }}</div>
                    <small class="text-muted">{{ $order->user->email ?? '-' }}</small>
                  </div>
                </div>
              </td>

              <td>{{ $order->product->name ?? 'Deleted Product' }}</td>
              <td>{{ $order->quantity }}</td>
              <td class="fw-semibold">{{ number_format($order->total_price, 2) }} Ks</td>

              <td>
                @php
                  $statusColors = [
                    'pending' => 'warning',
                    'processing' => 'info',
                    'completed' => 'success',
                    'cancelled' => 'danger',
                  ];
                @endphp
                <span
                  class="badge rounded-pill bg-{{ $statusColors[$order->status] ?? 'secondary' }} px-3 py-1 text-capitalize">
                  {{ $order->status }}
                </span>
              </td>

              <td class="text-muted small">{{ $order->created_at->format('d M Y') }}</td>

              <td class="text-end pe-3">
                <div class="dropdown">
                  <button class="btn btn-sm border-0" type="button" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                    {{-- Order Status --}}
                    <li>
                      <form action="{{ route('admin.orderstatus', $order->id) }}" method="POST" class="px-3 py-2">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                          <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                          <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing
                          </option>
                          <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                          <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                      </form>
                    </li>

                    <li>
                      <a class="dropdown-item" href="{{ route('admin.orderdetailpage', $order->id) }}">
                        <i class="fa-solid fa-eye me-2 text-info"></i> View Detail
                      </a>
                    </li>

                    <li>
                      <hr class="dropdown-divider">
                    </li>

                    <li>
                      <form action="{{ route('admin.orderdelete', $order->id) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="dropdown-item text-danger delete-btn">
                          <i class="fa-solid fa-trash me-2"></i> Delete
                        </button>
                      </form>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-5">
                <i class="fa-solid fa-cart-shopping fa-2x mb-2 d-block opacity-50"></i>
                No orders found
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($orders->hasPages())
      <div class="card-footer bg-transparent border-0 py-3">
        {{ $orders->links() }}
      </div>
    @endif
  </div>

@endsection
@section('scripts')
  <style>
    /* Beautiful Search Box */
    /* Mobile မှာ full width */
    @media (max-width: 575.98px) {
      .input-group {
        width: 100% !important;
      }
    }

    /*End Beautiful Search Box */
  </style>

@endsection