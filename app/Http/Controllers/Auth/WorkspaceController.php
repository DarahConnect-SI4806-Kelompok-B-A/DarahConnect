<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class WorkspaceController extends Controller
{
    /**
     * Display the role-protected workspace and active session security panel.
     */
    public function index(Request $request): View
    {
        $user = $request->user()->load(['region', 'facility']);

        return view('auth.workspace', [
            'user' => $user,
            'quickActions' => $this->resolveRoleActions($user->role),
            'activeSessions' => $this->getActiveSessions($request),
        ]);
    }

    /**
     * Build role-specific workspace shortcuts that automatically activate as features roll out.
     *
     * @return array<int, array<string, mixed>>
     */
    private function resolveRoleActions(UserRole $role): array
    {
        return match ($role) {
            UserRole::PENCARI_DARAH => [
                [
                    'title' => 'Ajukan Permohonan Darah Darurat',
                    'description' => 'Buat permohonan transfusi pasien beserta lampiran surat rujukan dokter atau simpan sebagai draf.',
                    'icon' => 'bi-file-earmark-medical-fill',
                    'url' => Route::has('emergency-requests.create') ? route('emergency-requests.create') : null,
                    'cta' => 'Buat Permohonan',
                ],
                [
                    'title' => 'Riwayat & Status Permohonan Saya',
                    'description' => 'Pantau status verifikasi UDD, jumlah komitmen pendonor, dan koordinasi titik temu di rumah sakit.',
                    'icon' => 'bi-clipboard2-pulse-fill',
                    'url' => Route::has('emergency-requests.index') ? route('emergency-requests.index') : null,
                    'cta' => 'Lihat Riwayat',
                ],
            ],
            UserRole::PENDONOR => [
                [
                    'title' => 'Eksplorasi Kebutuhan Darah Darurat',
                    'description' => 'Temukan pasien yang membutuhkan transfusi sesuai kompatibilitas golongan darah dan jarak terdekat.',
                    'icon' => 'bi-compass-fill',
                    'url' => Route::has('discovery.index') ? route('discovery.index') : null,
                    'cta' => 'Jelajahi Permohonan',
                ],
                [
                    'title' => 'Booking Jadwal Donor & Skrining Mandiri',
                    'description' => 'Pesan kuota sesi harian Gedung UDD atau Mobile Unit dan isi anamnesis digital pra-donor.',
                    'icon' => 'bi-calendar2-heart-fill',
                    'url' => Route::has('bookings.schedules') ? route('bookings.schedules') : null,
                    'cta' => 'Pesan Jadwal',
                ],
                [
                    'title' => 'Komitmen & Koordinasi Saya',
                    'description' => 'Kelola kehadiran donor darurat Anda dan konfirmasi titik temu di fasilitas kesehatan.',
                    'icon' => 'bi-person-check-fill',
                    'url' => Route::has('commitments.index') ? route('commitments.index') : null,
                    'cta' => 'Kelola Komitmen',
                ],
            ],
            UserRole::PETUGAS_UDD => [
                [
                    'title' => 'Antrean Verifikasi Permohonan Medis',
                    'description' => 'Tinjau berkas rujukan dokter yang masuk, setujui penayangan, atau minta revisi resmi.',
                    'icon' => 'bi-shield-check',
                    'url' => Route::has('verifications.index') ? route('verifications.index') : null,
                    'cta' => 'Buka Antrean',
                ],
                [
                    'title' => 'Manajemen Jadwal Gedung & Mobile Unit',
                    'description' => 'Atur kuota sesi harian gedung UDD, agenda donor keliling mitra, dan status operasional slot.',
                    'icon' => 'bi-building-fill-gear',
                    'url' => Route::has('schedules.index') ? route('schedules.index') : null,
                    'cta' => 'Kelola Jadwal',
                ],
            ],
            UserRole::PETUGAS_RS => [
                [
                    'title' => 'Layanan Bank Darah Rumah Sakit (BDRS)',
                    'description' => 'Kelola permintaan distribusi stok darah ke UDD, uji silang serasi (crossmatch), dan serah terima BAST.',
                    'icon' => 'bi-hospital-fill',
                    'url' => null,
                    'cta' => 'Modul Faskes RS',
                ],
            ],
            UserRole::ADMIN => [
                [
                    'title' => 'Konfigurasi Sistem & Mitra Faskes',
                    'description' => 'Pemeliharaan integritas akun petugas institusi, master wilayah, dan fasilitas kesehatan mitra.',
                    'icon' => 'bi-sliders2',
                    'url' => route('home'),
                    'cta' => 'Lihat Jejaring Mitra',
                ],
            ],
        };
    }

    /**
     * Retrieve and format active database sessions for the authenticated user.
     */
    private function getActiveSessions(Request $request): Collection
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        $currentSessionId = $request->session()->getId();

        return DB::table(config('session.table', 'sessions'))
            ->where('user_id', $request->user()->getAuthIdentifier())
            ->orderByDesc('last_activity')
            ->get()
            ->map(function (object $session) use ($currentSessionId): array {
                return [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address ?: 'Tidak diketahui',
                    'device' => $this->summarizeUserAgent((string) ($session->user_agent ?? '')),
                    'is_current' => $session->id === $currentSessionId,
                    'last_active' => Carbon::createFromTimestamp((int) $session->last_activity)->diffForHumans(),
                ];
            });
    }

    private function summarizeUserAgent(string $userAgent): string
    {
        if ($userAgent === '') {
            return 'Peramban Web';
        }

        $platform = match (true) {
            str_contains($userAgent, 'Windows') => 'Windows',
            str_contains($userAgent, 'Macintosh'), str_contains($userAgent, 'Mac OS') => 'macOS',
            str_contains($userAgent, 'Android') => 'Android',
            str_contains($userAgent, 'iPhone'), str_contains($userAgent, 'iPad') => 'iOS',
            str_contains($userAgent, 'Linux') => 'Linux',
            default => 'Perangkat Lain',
        };

        $browser = match (true) {
            str_contains($userAgent, 'Edg/') => 'Microsoft Edge',
            str_contains($userAgent, 'Chrome/') => 'Google Chrome',
            str_contains($userAgent, 'Firefox/') => 'Mozilla Firefox',
            str_contains($userAgent, 'Safari/') && ! str_contains($userAgent, 'Chrome/') => 'Safari',
            default => 'Peramban Web',
        };

        return "{$browser} pada {$platform}";
    }
}
