<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $demoAccounts = [];

        if (app()->isLocal()) {
            $demoAccounts = [
                [
                    'label' => 'Pencari Darah',
                    'email' => 'pencari@darahconnect.id',
                    'icon' => 'bi-heart-pulse-fill',
                ],
                [
                    'label' => 'Pendonor (O+)',
                    'email' => 'pendonor@darahconnect.id',
                    'icon' => 'bi-droplet-fill',
                ],
                [
                    'label' => 'Petugas UDD PMI',
                    'email' => 'udd.bandung@darahconnect.id',
                    'icon' => 'bi-shield-plus',
                ],
                [
                    'label' => 'Petugas BDRS RS',
                    'email' => 'bdrs.rshs@darahconnect.id',
                    'icon' => 'bi-hospital-fill',
                ],
            ];
        }

        return view('auth.login', [
            'demoAccounts' => $demoAccounts,
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();
        $roleLabel = $user->role->label();

        return redirect()
            ->intended(route('dashboard'))
            ->with('success', "Selamat datang kembali, {$user->name}. Anda masuk dengan hak akses {$roleLabel}.");
    }

    /**
     * Destroy an authenticated session (logout current device).
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('auth.login')
            ->with('status', 'Sesi login Anda telah diakhiri dengan aman.');
    }

    /**
     * Revoke a specific active session belonging to the current user.
     */
    public function destroySession(Request $request, string $sessionId): RedirectResponse
    {
        if ($sessionId === $request->session()->getId()) {
            return back()->with('warning', 'Gunakan tombol Keluar untuk mengakhiri sesi pada perangkat yang sedang Anda gunakan.');
        }

        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', $request->user()->getAuthIdentifier())
                ->where('id', $sessionId)
                ->delete();
        }

        return back()->with('success', 'Sesi perangkat terpilih berhasil dicabut.');
    }

    /**
     * Log out and revoke all other browser/device sessions using current password verification.
     */
    public function destroyOtherSessions(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('logoutOtherSessions', [
            'current_password' => ['required', 'string', 'current_password:web'],
        ], [
            'current_password.required' => 'Masukkan kata sandi saat ini untuk mengonfirmasi pencabutan sesi perangkat lain.',
            'current_password.current_password' => 'Kata sandi yang Anda masukkan tidak sesuai.',
        ]);

        Auth::logoutOtherDevices($validated['current_password']);

        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', $request->user()->getAuthIdentifier())
                ->where('id', '!=', $request->session()->getId())
                ->delete();
        }

        return back()->with('success', 'Seluruh sesi login pada perangkat lain telah berhasil dihentikan.');
    }
}
