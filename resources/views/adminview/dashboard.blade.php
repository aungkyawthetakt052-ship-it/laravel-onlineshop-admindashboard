<!-- resources/views/adminview/dashboard.blade.php -->
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin Dashboard')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->


  <script>
    (function () {
      const savedTheme = localStorage.getItem('theme') || 'light';
      document.documentElement.setAttribute('data-bs-theme', savedTheme);
    })();
  </script>

  <style>
    .customCard {
      width: 40%;
      height: 70px;
    }

    /* Cards were fixed at 40% width, which is unusable on a phone screen.
       Stack them to full width below the md breakpoint. */
    @media (max-width: 767.98px) {
      .customCard {
        width: 100% !important;
      }
    }

    .rounded-circle {
      text-align: center;
      align-content: center;
    }

    [data-bs-theme="dark"] .sidebar-link {
      color: white !important;
    }

    [data-bs-theme="light"] .sidebar-link {
      color: black !important;
    }

    .sidebar-link:hover {
      background-color: var(--bs-secondary-bg);
      border-radius: 5px;
    }

    .sidebar-link.active {
      background-color: var(--bs-primary);
      color: white !important;
      border-radius: 5px;
    }

    .sidebar-link.active i {
      color: white !important;
    }


    /* --- Content ဖက်ကိုပဲ scroll ဖြစ်စေဖို့ --- */
    .content-scroll {
      height: 100vh;
      overflow-y: auto;
    }

    /* --- Desktop မှာသာ Sidebar ကို fix ဖြစ်စေဖို့ --- */
    @media (min-width: 768px) {
      #sidebar {
        position: sticky;
        top: 0;
        height: 100vh;
        overflow-y: auto;
        /* menu item များလာရင် sidebar ကိုယ်တိုင် scroll ဖြစ်နိုင်ဖို့ */
      }
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg bg-transparent shadow-sm">
    <div class="container-fluid">
      <div class="ml-1 p-2">
        <a class="navbar-brand rounded-3" href="#">Dashboard</a>
      </div>

      <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar"
        aria-controls="sidebar" aria-expanded="false" aria-label="Toggle sidebar">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="d-flex align-items-center ms-auto gap-3">

        <!-- ========== Notification Bell ========== -->
        <div class="dropdown">
          <button class="btn btn-outline-secondary position-relative" type="button" id="notificationDropdown"
            data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-bell"></i>
            @if(auth()->user()->unreadNotifications->count() > 0)
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ auth()->user()->unreadNotifications->count() }}
              </span>
            @endif
          </button>

          <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notificationDropdown"
            style="width: 340px; max-height: 400px; overflow-y: auto;">
            <li class="dropdown-header d-flex justify-content-between align-items-center">
              <span>Notifications</span>
              @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-inline">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-link text-primary p-0">Mark all as read</button>
                </form>
              @endif
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            @forelse(auth()->user()->unreadNotifications as $notification)
              <li>
                <div class="dropdown-item-text px-3 py-2">
                  <div class="d-flex justify-content-between">
                    <p class="mb-1 small">{{ $notification->data['message'] ?? 'New notification' }}</p>
                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-link text-muted p-0" title="Mark as read">
                        <i class="fa-solid fa-check"></i>
                      </button>
                    </form>
                  </div>
                  <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
              </li>
              <li>
                <hr class="dropdown-divider m-0">
              </li>
            @empty
              <li>
                <div class="dropdown-item-text text-center text-muted py-4">
                  <i class="fa-regular fa-bell-slash fa-2x mb-2"></i>
                  <p class="mb-0">No new notifications</p>
                </div>
              </li>
            @endforelse
          </ul>
        </div>
        <!-- ========== End Notification Bell ========== -->

        <!-- Theme Toggle -->
        <button id="themeToggle" class="btn btn-outline-secondary">
          <i id="themeIcon" class="fa-solid fa-moon"></i>
          <span id="themeText">Dark</span>
        </button>
      </div>
    </div>
  </nav>

  <div class="d-flex">
    <div class="offcanvas-md offcanvas-start bg-body-tertiary" tabindex="-1" id="sidebar" style="width: 260px;">
      <div class="offcanvas-header d-md-none">
        <h5 class="offcanvas-title">Admin Menu</h5>
        <button type="button" class="btn-close btn-close-white d-md-none" data-bs-dismiss="offcanvas"
          aria-label="Close"></button>
      </div>
      <div class="offcanvas-body pt-3">
        <ul class="nav flex-column gap-1">
          <li class="nav-item">
            <a href="{{ route('admin.store') }}"
              class="nav-link sidebar-link {{ request()->routeIs('admin.store') ? 'active' : '' }}">
              <i class="fa-solid fa-home me-2"></i> Home
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('users') }}" class="nav-link sidebar-link {{ request()->is('users*') ? 'active' : '' }}">
              <i class="fa-solid fa-users me-2"></i> Users
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('products') }}"
              class="nav-link sidebar-link {{request()->routeIs('admin.productpage') ? 'active' : '' }}">
              <i class="fa-solid fa-suitcase me-2"></i> Products
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.orderpage') }}"
              class="nav-link sidebar-link {{ request()->routeIs('admin.orderpage', 'admin.orderdetailpage') ? 'active' : '' }}">
              <i class="fa-solid fa-cart-shopping me-2"></i> Orders
            </a>
          </li>
          <li class="nav-item mt-4">
            <a href="{{ route('admin.logout') }}" class="nav-link text-danger">
              <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- ONLY yield here — no hardcoded dashboard content anymore -->
    <div class="flex-grow-1 p-4 content-scroll">
      @yield('content')
    </div>
  </div>


  @yield('scripts')
</body>

</html>