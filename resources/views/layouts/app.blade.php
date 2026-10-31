<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title')@yield('title') — {{ config('app.name', 'DarahConnect') }}@else{{ config('app.name', 'DarahConnect') }} — Emergency Blood Network @endif</title>

    <!-- Bootstrap 5.3 & Icons (No jQuery) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- DarahConnect Apple Fluid Design System -->
    <link href="{{ asset('css/darahconnect-fluid.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <!-- Translucent Sticky Navigation (§12 Materials & Depth) -->
    <header class="dc-navbar" data-scrolled="false">
        <div class="container py-2">
            <nav class="d-flex align-items-center justify-content-between gap-3 py-1" aria-label="Navigasi Utama">
                <a href="{{ route('home') }}" class="d-inline-flex align-items-center text-decoration-none dc-pressable" aria-label="Beranda DarahConnect">
                    <img src="{{ asset('images/logo-darah-connect.svg') }}" alt="DarahConnect — Emergency Blood Network" height="38">
                </a>

                <div class="d-none d-lg-flex align-items-center gap-1">
                    <a href="{{ route('home') }}" class="dc-nav-pill {{ request()->routeIs('home') ? 'active' : '' }}">
                        Beranda
                    </a>

                    @if (Route::has('discovery.index'))
                        <a href="{{ route('discovery.index') }}" class="dc-nav-pill {{ request()->routeIs('discovery.*') ? 'active' : '' }}">
                            Kebutuhan Darurat
                        </a>
                    @endif

                    @if (Route::has('bookings.schedules'))
                        <a href="{{ route('bookings.schedules') }}" class="dc-nav-pill {{ request()->routeIs('bookings.*') ? 'active' : '' }}">
                            Jadwal Donor
                        </a>
                    @endif

                    @auth
                        @if (Route::has('dashboard'))
                            <a href="{{ route('dashboard') }}" class="dc-nav-pill {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
                                Ruang Kerja
                            </a>
                        @endif
                    @endauth
                </div>

                <div class="d-flex align-items-center gap-2">
                    @guest
                        @if (Route::has('auth.login'))
                            <a href="{{ route('auth.login') }}" class="btn btn-dc-secondary btn-sm px-3">
                                Masuk
                            </a>
                        @endif
                        @if (Route::has('auth.register'))
                            <a href="{{ route('auth.register') }}" class="btn btn-dc-primary btn-sm px-3">
                                Daftar Akun
                            </a>
                        @endif
                    @else
                        <span class="dc-pill dc-pill-neutral d-none d-sm-inline-flex">
                            <i class="bi bi-person-circle text-danger"></i>
                            {{ auth()->user()->name }}
                        </span>
                        @if (Route::has('auth.logout'))
                            <form action="{{ route('auth.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-dc-secondary btn-sm">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span class="d-none d-md-inline">Keluar</span>
                                </button>
                            </form>
                        @endif
                    @endguest
                </div>
            </nav>
        </div>
    </header>

    <!-- Feedback Banners (§16: Status, Completion, Warning, Error) -->
    @if (session('success') || session('status') || session('warning') || session('error'))
        <div class="container mt-3">
            @if (session('success'))
                <div class="dc-card p-3 border-success-subtle bg-success-subtle text-success-emphasis d-flex align-items-center gap-2" role="status">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <div class="fw-medium">{{ session('success') }}</div>
                </div>
            @endif
            @if (session('status'))
                <div class="dc-card p-3 border-info-subtle bg-info-subtle text-info-emphasis d-flex align-items-center gap-2" role="status">
                    <i class="bi bi-info-circle-fill fs-5"></i>
                    <div class="fw-medium">{{ session('status') }}</div>
                </div>
            @endif
            @if (session('warning'))
                <div class="dc-card p-3 border-warning-subtle bg-warning-subtle text-warning-emphasis d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <div class="fw-medium">{{ session('warning') }}</div>
                </div>
            @endif
            @if (session('error'))
                <div class="dc-card p-3 border-danger-subtle bg-danger-subtle text-danger-emphasis d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-x-octagon-fill fs-5"></i>
                    <div class="fw-medium">{{ session('error') }}</div>
                </div>
            @endif
        </div>
    @endif

    <main class="flex-grow-1">
        @yield('content')
    </main>

    <footer class="border-top bg-white mt-5 py-4">
        <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ asset('images/logo-darah-connect.svg') }}" alt="DarahConnect" height="30">
                <span class="dc-caption">Jejaring tanggap darurat transfusi & pelayanan Unit Donor Darah terintegrasi.</span>
            </div>
            <div class="dc-caption">
                &copy; {{ date('Y') }} DarahConnect &middot; Emergency Blood Network
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="{{ asset('js/darahconnect-fluid.js') }}"></script>
    @stack('scripts')
</body>
</html>
