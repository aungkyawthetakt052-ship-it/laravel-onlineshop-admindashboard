<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Online Shop')</title>

    {{-- Theme flash guard --}}
    <script>
        (function () {
            const saved = localStorage.getItem('theme');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = saved || (systemPrefersDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --ink-900: #0B1220;
            --ink-800: #111827;
            --ink-700: #1e293b;
            --gold-500: #F5B700;
            --gold-600: #D99A00;
            --teal-400: #2DD4BF;
            --mist-100: #F8FAFC;
            --slate-300: #CBD5E1;
            --slate-400: #94A3B8;
            --slate-500: #64748B;
            --radius: 12px;
            --shadow-lg: 0 10px 40px rgba(0, 0, 0, .18);
        }

        /* ===== Base ===== */
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: #ffffff;
            color: #1e293b;
            transition: background-color .25s ease, color .25s ease;
            -webkit-font-smoothing: antialiased;
        }

        body.nav-open {
            overflow: hidden;
        }

        [data-bs-theme="dark"] body {
            background-color: #0f172a;
            color: #f1f5f9;
        }

        [data-bs-theme="dark"] .card {
            background-color: #1e293b;
            border-color: rgba(255, 255, 255, .08);
            color: #f1f5f9;
        }

        [data-bs-theme="dark"] .table {
            --bs-table-color: #f1f5f9;
            --bs-table-bg: transparent;
            color: #f1f5f9;
        }

        [data-bs-theme="dark"] .dropdown-menu {
            --bs-dropdown-bg: #1e293b;
            --bs-dropdown-link-color: #f1f5f9;
            --bs-dropdown-link-hover-bg: #334155;
            --bs-dropdown-border-color: rgba(255, 255, 255, .08);
        }

        /* ===== NAVBAR ===== */
        .site-nav {
            position: sticky;
            top: 0;
            z-index: 1050;
            background: linear-gradient(180deg, var(--ink-900) 0%, var(--ink-800) 100%);
            box-shadow: 0 4px 24px rgba(0, 0, 0, .28);
            border-bottom: 1px solid rgba(255, 255, 255, .05);
            transition: padding .25s ease, box-shadow .25s ease;
        }

        .site-nav.is-scrolled {
            box-shadow: 0 6px 30px rgba(0, 0, 0, .4);
        }

        .nav-inner {
            display: flex;
            align-items: center;
            gap: 1.75rem;
            padding: 0.9rem 1.75rem;
            max-width: 1280px;
            margin: 0 auto;
            transition: padding .25s ease;
        }

        .site-nav.is-scrolled .nav-inner {
            padding-block: 0.6rem;
        }

        /* Brand */
        .brand {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            text-decoration: none;
            flex-shrink: 0;
        }

        .brand-mark {
            width: 40px;
            height: 40px;
            border-radius: 11px;
            background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ink-900);
            font-size: 1.05rem;
            box-shadow: 0 4px 12px rgba(245, 183, 0, .35);
            flex-shrink: 0;
        }

        .brand-text {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 1.3rem;
            color: var(--mist-100);
            letter-spacing: -0.03em;
        }

        .brand-accent {
            color: var(--gold-500);
        }

        .nav-search {
            max-height: 0;
            overflow: hidden;
            transition: max-height .35s cubic-bezier(0.4, 0, 0.2, 1);
            border-top: 1px solid rgba(255, 255, 255, .06);
            background: rgba(0, 0, 0, .15);
        }

        .nav-search.is-open {
            max-height: 100px;
            /* a bit higher is safer */
        }

        /* Nav links + single sliding indicator */
        .nav-links {
            position: relative;
            display: flex;
            align-items: center;
        }

        .nav-list {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 0.2rem;
            position: relative;
        }

        .nav-link {
            display: block;
            padding: 0.55rem 1.15rem;
            font-size: 0.93rem;
            font-weight: 500;
            color: var(--slate-400) !important;
            text-decoration: none;
            border-radius: 999px;
            position: relative;
            z-index: 2;
            transition: color .2s ease;
            white-space: nowrap;
        }

        .nav-link:hover {
            color: #ffffff !important;
        }

        .nav-link.is-active {
            color: var(--ink-900) !important;
            font-weight: 600;
        }

        .nav-indicator {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 0;
            background: var(--gold-500);
            border-radius: 999px;
            z-index: 1;
            opacity: 0;
            pointer-events: none;
            transition: left .28s cubic-bezier(.4, 0, .2, 1),
                width .28s cubic-bezier(.4, 0, .2, 1),
                opacity .2s ease;
            box-shadow: 0 2px 10px rgba(245, 183, 0, .4);
        }

        .nav-indicator.is-visible {
            opacity: 1;
        }

        /* Right actions */
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            margin-left: auto;
            flex-shrink: 0;
        }

        .icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 11px;
            border: 1px solid rgba(255, 255, 255, .09);
            background: rgba(255, 255, 255, .05);
            color: var(--mist-100);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            position: relative;
            transition: all .2s ease;
            cursor: pointer;
            flex-shrink: 0;
        }

        .icon-btn:hover {
            background: rgba(245, 183, 0, .15);
            border-color: var(--gold-500);
            color: var(--gold-500);
            transform: translateY(-1px);
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            border-radius: 999px;
            background: var(--gold-500);
            color: var(--ink-900);
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.68rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            box-shadow: 0 2px 6px rgba(245, 183, 0, .45);
        }

        .cart-badge[data-count="0"] {
            display: none;
        }

        /* User chip */
        .user-chip {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 999px;
            padding: 0.3rem 0.9rem 0.3rem 0.35rem;
            color: var(--mist-100);
            transition: all .2s ease;
        }

        .user-chip:hover {
            background: rgba(255, 255, 255, .09);
            border-color: rgba(255, 255, 255, .18);
        }

        .user-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--teal-400), #14b8a6);
            color: var(--ink-900);
            font-weight: 700;
            font-size: 0.78rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .user-name {
            font-size: 0.88rem;
            font-weight: 500;
        }

        .btn-login {
            background: var(--gold-500);
            color: var(--ink-900) !important;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.5rem 1.25rem;
            border-radius: 999px;
            text-decoration: none;
            transition: all .2s ease;
            box-shadow: 0 2px 10px rgba(245, 183, 0, .3);
            white-space: nowrap;
        }

        .btn-login:hover {
            background: var(--gold-600);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(245, 183, 0, .4);
        }

        /* Expandable search */
        .nav-search {
            max-height: 0;
            overflow: hidden;
            transition: max-height .3s ease;
            border-top: 1px solid rgba(255, 255, 255, .06);
            background: rgba(0, 0, 0, .15);
        }

        .nav-search.is-open {
            max-height: 90px;
        }

        .search-form {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.95rem 1.75rem;
            max-width: 1280px;
            margin: 0 auto;
            color: var(--slate-400);
        }

        .search-input {
            flex: 1;
            background: rgba(255, 255, 255, .07);
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 10px;
            padding: 0.65rem 1rem;
            color: var(--mist-100);
            font-size: 0.95rem;
            transition: border-color .2s;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--gold-500);
            background: rgba(255, 255, 255, .1);
        }

        .search-input::placeholder {
            color: var(--slate-400);
        }

        .search-submit {
            background: var(--gold-500);
            color: var(--ink-900);
            border: none;
            border-radius: 10px;
            padding: 0.65rem 1.3rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: background .2s;
            cursor: pointer;
        }

        .search-submit:hover {
            background: var(--gold-600);
        }

        /* Mobile toggle (hamburger) */
        .nav-toggle {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: none;
            border: none;
            padding: 8px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .nav-toggle span {
            width: 22px;
            height: 2px;
            background: var(--mist-100);
            border-radius: 2px;
            transition: transform .25s ease, opacity .25s ease;
        }

        .nav-toggle.is-open span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .nav-toggle.is-open span:nth-child(2) {
            opacity: 0;
        }

        .nav-toggle.is-open span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        /* Mobile drawer backdrop */
        .nav-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s ease;
            z-index: 1035;
        }

        .nav-backdrop.is-open {
            opacity: 1;
            pointer-events: auto;
        }

        /* Alert */
        .alert-toast {
            border-radius: 12px;
            border: none;
            box-shadow: var(--shadow-lg);
        }

        /* ===== Mobile ===== */
        @media (max-width: 991.98px) {
            .nav-toggle {
                display: flex;
            }

            .nav-links {
                position: fixed;
                top: 0;
                right: 0;
                width: min(300px, 82vw);
                height: 100vh;
                background: var(--ink-900);
                flex-direction: column;
                justify-content: flex-start;
                align-items: stretch;
                padding: 5.5rem 1.25rem 1.5rem;

                transform: translateX(100%);
                transition: transform .32s cubic-bezier(.4, 0, .2, 1);
                will-change: transform;

                box-shadow: -10px 0 30px rgba(0, 0, 0, .35);
                z-index: 1040;
            }

            .nav-links.is-open {
                transform: translateX(0);
            }

            .nav-list {
                flex-direction: column;
                width: 100%;
                gap: 0.3rem;
            }

            .nav-indicator {
                display: none;
            }

            .nav-link {
                border-radius: 10px;
                padding: 0.8rem 1.1rem;
            }

            .nav-link.is-active,
            .nav-link:hover {
                background: rgba(245, 183, 0, .12);
                color: var(--gold-500) !important;
            }
        }

        /* ===== Content helpers ===== */
        .page-section {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem;
        }

        .section-title {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 1.75rem;
            letter-spacing: -0.03em;
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body>

    <nav class="site-nav" id="siteNav">
        <div class="nav-inner">

            <a class="brand" href="{{ url('/') }}">
                <span class="brand-mark"><i class="fa-solid fa-bag-shopping"></i></span>
                <span class="brand-text">Online<span class="brand-accent">shop</span></span>
            </a>

            <button class="nav-toggle" id="navToggle" aria-label="Toggle menu" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>

            <div class="nav-links" id="navLinks">
                <ul class="nav-list" id="navList">
                    <li><a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'is-active' : '' }}">Home</a>
                    </li>
                    <li><a href="{{ url('shop') }}"
                            class="nav-link {{ request()->is('shop*') ? 'is-active' : '' }}">Shop</a></li>
                    <li><a href="{{ url('cart') }}"
                            class="nav-link {{ request()->is('cart') ? 'is-active' : '' }}">Cart</a></li>
                    <li><a href="{{ url('contact') }}"
                            class="nav-link {{ request()->is('contact') ? 'is-active' : '' }}">Contact</a></li>
                    <li><a href="{{ url('about') }}"
                            class="nav-link {{ request()->is('about') ? 'is-active' : '' }}">About</a></li>
                </ul>
                <span class="nav-indicator" id="navIndicator"></span>
            </div>

            <div class="nav-actions">
                <button class="icon-btn" id="searchToggle" aria-label="Search" title="Search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>

                <a href="{{ url('cart') }}" class="icon-btn" title="Cart">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="cart-badge" data-count="{{ $cartCount ?? 0 }}">{{ $cartCount ?? 0 }}</span>
                </a>

                <button class="icon-btn" id="themeToggleBtn" title="Toggle dark mode">
                    <i class="fa-solid fa-moon" id="themeIcon"></i>
                </button>

                @auth
                    <div class="dropdown user-dropdown">
                        <button class="user-chip dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            <span class="user-name">{{ Auth::user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2">
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                                    <i class="fa-solid fa-user-pen me-2 text-primary"></i> My Account
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item py-2 text-danger" href="{{ route('admin.logout') }}">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-login">

                       <i class="fa-solid fa-user text-black me-2"></i> Log in / Register
                    </a>
                @endauth
            </div>
        </div>

        <div class="nav-search" id="navSearch">
            <form class="search-form" role="search" action="{{ url('shop') }}" method="GET">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="search" name="search" class="search-input"
                    placeholder="Search products, brands, categories..." value="{{ request('search') }}">
                <button type="submit" class="search-submit">Search</button>
            </form>
        </div>
    </nav>

    <div class="nav-backdrop" id="navBackdrop"></div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3 alert-toast"
            id="successAlert" role="alert" style="z-index: 1055;">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    @hasSection('footer')
        @yield('footer')
    @endif

    @yield('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const searchToggle = document.getElementById('searchToggle');
            const navSearch = document.getElementById('navSearch');
            const searchInput = navSearch.querySelector('.search-input');

            // Toggle search bar
            searchToggle.addEventListener('click', function (e) {
                e.stopPropagation();
                navSearch.classList.toggle('is-open');

                // Auto focus input when opened
                if (navSearch.classList.contains('is-open')) {
                    setTimeout(() => searchInput.focus(), 300);
                }
            });

            // Close when clicking outside
            document.addEventListener('click', function (e) {
                if (!navSearch.contains(e.target) && !searchToggle.contains(e.target)) {
                    navSearch.classList.remove('is-open');
                }
            });

            // Close with Escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    navSearch.classList.remove('is-open');
                }
            });

        });
    </script>

</body>

</html>