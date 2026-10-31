<?php

namespace App\Http\Controllers\Auth;

use App\Enums\BloodType;
use App\Enums\Rhesus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Display the multi-role registration view.
     */
    public function create(): View
    {
        $regionsByProvince = Region::query()
            ->orderBy('province_name')
            ->orderBy('name')
            ->get()
            ->groupBy('province_name');

        return view('auth.register', [
            'roles' => UserRole::publicRegistrationRoles(),
            'bloodTypes' => BloodType::cases(),
            'rhesusTypes' => Rhesus::cases(),
            'regionsByProvince' => $regionsByProvince,
        ]);
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $isDonor = $validated['role'] === UserRole::PENDONOR->value;

        $user = DB::transaction(function () use ($validated, $isDonor): User {
            $region = Region::findOrFail($validated['region_id']);

            return User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'region_id' => $region->id,
                'address' => $validated['address'] ?? null,
                'latitude' => $region->latitude,
                'longitude' => $region->longitude,
                'blood_type' => $isDonor ? $validated['blood_type'] : null,
                'rhesus' => $isDonor ? $validated['rhesus'] : null,
                'gender' => $isDonor ? $validated['gender'] : null,
                'birth_date' => $isDonor ? $validated['birth_date'] : null,
                'weight_kg' => $isDonor ? $validated['weight_kg'] : null,
                'is_active' => true,
            ]);
        });

        $roleLabel = $user->role->label();

        if (Route::has('auth.login')) {
            return redirect()
                ->route('auth.login')
                ->with('registered_email', $user->email)
                ->with('success', "Registrasi berhasil! Akun {$roleLabel} atas nama {$user->name} telah aktif. Silakan masuk menggunakan kredensial Anda.");
        }

        return redirect()
            ->route('auth.register')
            ->with('registered_account', [
                'name' => $user->name,
                'email' => $user->email,
                'role_label' => $roleLabel,
                'blood_group' => $user->blood_group_label,
                'region' => $user->region?->full_label,
            ])
            ->with('success', "Registrasi berhasil! Akun {$roleLabel} atas nama {$user->name} telah resmi terdaftar di DarahConnect.");
    }
}
