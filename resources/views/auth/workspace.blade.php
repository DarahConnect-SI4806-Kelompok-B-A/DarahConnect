@extends('layouts.app')

@section('title', 'Ruang Kerja & Keamanan Sesi')

@section('content')
<section class="py-4 py-lg-5">
    <div class="container">
        <!-- Identity & Role Header Card -->
        <div class="dc-card p-4 p-md-5 mb-4">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <span class="dc-pill dc-pill-critical">
                            <i class="bi bi-person-badge-fill"></i>
                            {{ $user->role->label() }}
                        </span>
                        @if ($user->facility)
                            <span class="dc-pill dc-pill-neutral">
                                <i class="bi bi-hospital"></i>
                                {{ $user->facility->name }}
                            </span>
                        @endif
                        @if ($user->region)
                            <span class="dc-pill dc-pill-neutral">
                                <i class="bi bi-geo-alt"></i>
                                {{ $user->region->name }}
                            </span>
                        @endif
                    </div>

                    <h1 class="dc-title-1 mb-2">Halo, {{ $user->name }}</h1>
                    <p class="dc-caption mb-0">
                        {{ $user->role->description() }}
                    </p>
                </div>

                <div class="col-lg-4 d-flex align-items-center justify-content-lg-end gap-3">
                    @if ($user->blood_group_label)
                        <div class="d-flex align-items-center gap-2 p-2 pe-3 rounded-4 border bg-light">
                            <span class="dc-blood-badge">{{ $user->blood_group_label }}</span>
                            <div>
                                <div class="dc-eyebrow text-secondary">Golongan</div>
                                <div class="small fw-semibold">Profil Pendonor</div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('auth.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-dc-secondary">
                            <i class="bi bi-box-arrow-right text-danger"></i>
                            Akhiri Sesi
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row g-4 align-items-start">
            <!-- Left Column: Role-Specific Quick Actions -->
            <div class="col-lg-7">
                <div class="mb-3">
                    <div class="dc-eyebrow">Layanan Sesuai Hak Akses</div>
                    <h2 class="dc-title-2 mt-1 mb-0">Menu Operasional Peran Anda</h2>
                </div>

                <div class="vstack gap-3">
                    @foreach ($quickActions as $action)
                        <div class="dc-card p-4">
                            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="rounded-4 bg-danger-subtle text-danger d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 3rem; height: 3rem;">
                                        <i class="bi {{ $action['icon'] }} fs-4"></i>
                                    </div>
                                    <div>
                                        <h3 class="dc-title-3 mb-1">{{ $action['title'] }}</h3>
                                        <p class="dc-caption mb-0">{{ $action['description'] }}</p>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    @if (!empty($action['url']))
                                        <a href="{{ $action['url'] }}" class="btn btn-dc-primary btn-sm px-3">
                                            {{ $action['cta'] }} <i class="bi bi-arrow-right"></i>
                                        </a>
                                    @else
                                        <span class="dc-pill dc-pill-neutral">Segera Hadir</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Column: Active Login Session Management -->
            <div class="col-lg-5">
                <div class="dc-card p-4">
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <div class="dc-eyebrow">Privasi &amp; Keamanan Akun</div>
                            <h2 class="dc-title-3 mt-1 mb-0">Manajemen Sesi Login Aktif</h2>
                        </div>
                        <span class="dc-pill dc-pill-success dc-tabular">
                            <i class="bi bi-shield-check"></i>
                            {{ $activeSessions->count() }} Sesi
                        </span>
                    </div>

                    <p class="dc-caption mb-3">
                        Pantau perangkat yang sedang terhubung dengan akun Anda. Jika terdapat aktivitas yang tidak dikenali, segera cabut sesi perangkat terkait.
                    </p>

                    @if ($errors->logoutOtherSessions->any())
                        <div class="p-3 mb-3 rounded-4 bg-danger-subtle border border-danger-subtle text-danger-emphasis small" role="alert">
                            <i class="bi bi-exclamation-circle-fill me-1"></i>
                            {{ $errors->logoutOtherSessions->first('current_password') }}
                        </div>
                    @endif

                    <div class="vstack gap-2 mb-3">
                        @forelse ($activeSessions as $sessionItem)
                            <div class="p-3 rounded-4 border {{ $sessionItem['is_current'] ? 'bg-light border-danger border-opacity-25' : 'bg-white' }}">
                                <div class="d-flex align-items-center justify-content-between gap-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="bi {{ str_contains($sessionItem['device'], 'Android') || str_contains($sessionItem['device'], 'iOS') ? 'bi-phone' : 'bi-laptop' }} fs-4 {{ $sessionItem['is_current'] ? 'text-danger' : 'text-secondary' }}"></i>
                                        <div>
                                            <div class="fw-semibold small d-flex align-items-center gap-2">
                                                <span>{{ $sessionItem['device'] }}</span>
                                                @if ($sessionItem['is_current'])
                                                    <span class="dc-pill dc-pill-critical py-0 px-2" style="font-size: 0.7rem;">Perangkat Ini</span>
                                                @endif
                                            </div>
                                            <div class="dc-caption dc-tabular">
                                                IP {{ $sessionItem['ip_address'] }} &middot; {{ $sessionItem['last_active'] }}
                                            </div>
                                        </div>
                                    </div>

                                    @if (! $sessionItem['is_current'])
                                        <form action="{{ route('auth.sessions.destroy', $sessionItem['id']) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-dc-soft btn-sm">
                                                Cabut
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-3 rounded-4 border bg-light dc-caption text-center">
                                Sesi aktif saat ini terlindungi melalui token terenkripsi peramban Anda.
                            </div>
                        @endforelse
                    </div>

                    <div class="d-grid">
                        <button type="button" class="btn btn-dc-secondary btn-sm" data-dc-open-sheet="revokeOtherSessionsSheet">
                            <i class="bi bi-shield-slash text-danger"></i>
                            Keluar dari Semua Perangkat Lain
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Fluid Draggable Bottom Sheet for Confirming Logout of Other Devices (§2, §5, §6, §12) -->
<div class="dc-sheet-backdrop" data-dc-sheet-backdrop="revokeOtherSessionsSheet"></div>
<div
    class="dc-sheet-panel"
    data-dc-sheet="revokeOtherSessionsSheet"
    role="dialog"
    aria-modal="true"
    aria-labelledby="revokeSheetTitle"
    aria-hidden="true"
>
    <div class="dc-sheet-handle-area" aria-hidden="true">
        <div class="dc-sheet-handle"></div>
    </div>
    <div class="p-4 pt-2">
        <div class="d-flex align-items-start justify-content-between gap-3 mb-2">
            <div>
                <div class="dc-eyebrow">Konfirmasi Keamanan</div>
                <h3 class="dc-title-3 mb-1" id="revokeSheetTitle">Hentikan Sesi di Perangkat Lain</h3>
            </div>
            <button type="button" class="btn btn-dc-secondary btn-sm" data-dc-close-sheet="revokeOtherSessionsSheet" aria-label="Tutup panel">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <p class="dc-caption mb-3">
            Masukkan kata sandi akun Anda saat ini untuk memvalidasi identitas dan mencabut seluruh sesi login aktif selain perangkat yang sedang Anda gunakan.
        </p>

        <form action="{{ route('auth.sessions.destroy-others') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="mb-3">
                <label for="current_password" class="form-label">Kata Sandi Saat Ini</label>
                <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    class="form-control"
                    placeholder="Masukkan kata sandi Anda"
                    autocomplete="current-password"
                    required
                >
            </div>
            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-dc-secondary" data-dc-close-sheet="revokeOtherSessionsSheet">
                    Batal
                </button>
                <button type="submit" class="btn btn-dc-primary">
                    <i class="bi bi-shield-check"></i>
                    Konfirmasi &amp; Cabut Sesi Lain
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@if ($errors->logoutOtherSessions->any())
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        window.DarahSheets?.get('revokeOtherSessionsSheet')?.open();
    });
</script>
@endpush
@endif
