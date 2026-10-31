@extends('layouts.app')

@section('title', 'Masuk ke Akun')

@section('content')
<section class="py-4 py-lg-5">
    <div class="container">
        <div class="row justify-content-center align-items-start g-4 g-xl-5">
            <div class="col-lg-5 col-xl-5">
                <div class="dc-card p-4 p-md-5">
                    <div class="mb-4">
                        <div class="dc-eyebrow">Otentikasi Multi-Peran</div>
                        <h1 class="dc-title-1 mt-1 mb-1">Masuk ke DarahConnect</h1>
                        <p class="dc-caption mb-0">
                            Gunakan email dan kata sandi resmi Anda untuk mengakses layanan sesuai hak akses peran.
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="p-3 mb-4 rounded-4 bg-danger-subtle border border-danger-subtle text-danger-emphasis" role="alert">
                            <div class="d-flex align-items-start gap-2 small">
                                <i class="bi bi-exclamation-circle-fill mt-1"></i>
                                <div>
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('auth.login.store') }}" method="POST" id="loginForm" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', session('registered_email')) }}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="nama@email.com"
                                autocomplete="username"
                                autofocus
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Kata Sandi</label>
                            <div class="input-group">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Masukkan kata sandi Anda"
                                    autocomplete="current-password"
                                    required
                                >
                                <button type="button" class="btn btn-dc-secondary px-3" id="toggleLoginPassword" aria-label="Tampilkan atau sembunyikan kata sandi">
                                    <i class="bi bi-eye" id="toggleLoginPasswordIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="remember"
                                    id="remember"
                                    value="1"
                                    @checked(old('remember'))
                                >
                                <label class="form-check-label small text-secondary" for="remember">
                                    Ingat sesi pada perangkat ini
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-dc-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i>
                                Masuk Sekarang
                            </button>
                        </div>
                    </form>

                    <div class="pt-4 mt-4 border-top text-center dc-caption">
                        Belum memiliki akun Pencari Darah atau Pendonor?
                        <a href="{{ route('auth.register') }}" class="text-danger fw-semibold text-decoration-none">
                            Daftar Akun Baru
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-xl-4">
                <div class="vstack gap-3">
                    <div class="dc-card p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-4 bg-danger-subtle text-danger d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 2.75rem; height: 2.75rem;">
                                <i class="bi bi-shield-lock-fill fs-5"></i>
                            </div>
                            <div>
                                <div class="dc-eyebrow">Proteksi Sesi</div>
                                <h2 class="dc-title-3 mb-0">Akses Terisolasi Per Peran</h2>
                            </div>
                        </div>
                        <p class="dc-caption mb-0">
                            Setiap sesi dilindungi regenerasi token anti-<em>session fixation</em>, pembatasan percobaan masuk berlebih, serta kendali pencabutan sesi perangkat aktif dari Ruang Kerja Anda.
                        </p>
                    </div>

                    @if (!empty($demoAccounts))
                        <div class="dc-card p-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="dc-eyebrow text-secondary">Mode Pengembangan Lokal</span>
                                <span class="dc-pill dc-pill-neutral">Sandi: Password123</span>
                            </div>
                            <p class="dc-caption mb-3">
                                Pilih salah satu peran di bawah untuk mengisi kredensial akun uji coba resmi secara instan:
                            </p>
                            <div class="row g-2">
                                @foreach ($demoAccounts as $demo)
                                    <div class="col-6">
                                        <button
                                            type="button"
                                            class="btn btn-dc-secondary btn-sm w-100 justify-content-start text-start py-2 px-3 js-fill-demo"
                                            data-email="{{ $demo['email'] }}"
                                            data-password="Password123"
                                        >
                                            <i class="bi {{ $demo['icon'] }} text-danger"></i>
                                            <span class="text-truncate">{{ $demo['label'] }}</span>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const toggleBtn = document.getElementById('toggleLoginPassword');
        const toggleIcon = document.getElementById('toggleLoginPasswordIcon');

        toggleBtn?.addEventListener('click', () => {
            const isText = passwordInput.type === 'text';
            passwordInput.type = isText ? 'password' : 'text';
            toggleIcon.className = isText ? 'bi bi-eye' : 'bi bi-eye-slash';
        });

        document.querySelectorAll('.js-fill-demo').forEach((btn) => {
            btn.addEventListener('click', () => {
                if (emailInput) emailInput.value = btn.dataset.email || '';
                if (passwordInput) passwordInput.value = btn.dataset.password || '';
                emailInput?.focus();
            });
        });
    });
</script>
@endpush
