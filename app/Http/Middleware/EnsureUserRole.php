<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Ensure the authenticated user is active and possesses one of the permitted roles.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('auth.login');
        }

        if (! $user->is_active) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('auth.login')
                ->with('error', 'Sesi Anda dihentikan karena status akun sedang dinonaktifkan.');
        }

        if ($roles !== [] && ! $user->hasRole(...$roles)) {
            abort(403, 'Anda tidak memiliki hak akses peran untuk membuka halaman ini.');
        }

        return $next($request);
    }
}
