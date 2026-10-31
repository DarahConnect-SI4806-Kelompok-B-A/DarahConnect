<?php

namespace Database\Seeders;

use App\Enums\BloodType;
use App\Enums\Rhesus;
use App\Enums\UserRole;
use App\Models\Facility;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffUserSeeder extends Seeder
{
    /**
     * Seed institutional staff accounts and verified demo users across roles.
     */
    public function run(): void
    {
        $bandungRegion = Region::where('code', '3273')->first() ?? Region::first();
        $jakartaRegion = Region::where('code', '3171')->first() ?? $bandungRegion;

        $uddBandung = Facility::where('code', 'UDD-BDG-01')->first();
        $uddJakarta = Facility::where('code', 'UDD-DKI-01')->first();
        $rsHasanSadikin = Facility::where('code', 'RS-BDG-01')->first();

        $defaultPassword = Hash::make('Password123');

        $accounts = [
            [
                'email' => 'admin@darahconnect.id',
                'name' => 'Administrator Sistem DarahConnect',
                'role' => UserRole::ADMIN->value,
                'phone' => '081100000001',
                'region_id' => $jakartaRegion?->id,
                'facility_id' => null,
                'latitude' => $jakartaRegion?->latitude,
                'longitude' => $jakartaRegion?->longitude,
            ],
            [
                'email' => 'udd.bandung@darahconnect.id',
                'name' => 'dr. Ratna Kusuma (Petugas UDD Bandung)',
                'role' => UserRole::PETUGAS_UDD->value,
                'phone' => '081100000002',
                'region_id' => $bandungRegion?->id,
                'facility_id' => $uddBandung?->id,
                'latitude' => $uddBandung?->latitude ?? $bandungRegion?->latitude,
                'longitude' => $uddBandung?->longitude ?? $bandungRegion?->longitude,
            ],
            [
                'email' => 'udd.jakarta@darahconnect.id',
                'name' => 'dr. Hendra Wijaya (Petugas UDD DKI)',
                'role' => UserRole::PETUGAS_UDD->value,
                'phone' => '081100000003',
                'region_id' => $jakartaRegion?->id,
                'facility_id' => $uddJakarta?->id,
                'latitude' => $uddJakarta?->latitude ?? $jakartaRegion?->latitude,
                'longitude' => $uddJakarta?->longitude ?? $jakartaRegion?->longitude,
            ],
            [
                'email' => 'bdrs.rshs@darahconnect.id',
                'name' => 'Analis BDRS RSHS Bandung',
                'role' => UserRole::PETUGAS_RS->value,
                'phone' => '081100000004',
                'region_id' => $bandungRegion?->id,
                'facility_id' => $rsHasanSadikin?->id,
                'latitude' => $rsHasanSadikin?->latitude ?? $bandungRegion?->latitude,
                'longitude' => $rsHasanSadikin?->longitude ?? $bandungRegion?->longitude,
            ],
            [
                'email' => 'pendonor@darahconnect.id',
                'name' => 'Dimas Aditya',
                'role' => UserRole::PENDONOR->value,
                'phone' => '081211110005',
                'region_id' => $bandungRegion?->id,
                'facility_id' => null,
                'blood_type' => BloodType::O->value,
                'rhesus' => Rhesus::POSITIVE->value,
                'gender' => 'L',
                'birth_date' => '1999-08-17',
                'weight_kg' => 68,
                'address' => 'Kec. Coblong, Kota Bandung',
                'latitude' => -6.8914800,
                'longitude' => 107.6106590,
            ],
            [
                'email' => 'pencari@darahconnect.id',
                'name' => 'Nadia Maharani',
                'role' => UserRole::PENCARI_DARAH->value,
                'phone' => '081211110006',
                'region_id' => $bandungRegion?->id,
                'facility_id' => null,
                'address' => 'Kec. Sukajadi, Kota Bandung',
                'latitude' => -6.8938100,
                'longitude' => 107.5944200,
            ],
        ];

        foreach ($accounts as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                array_merge($account, [
                    'password' => $defaultPassword,
                    'email_verified_at' => now(),
                    'is_active' => true,
                ])
            );
        }
    }
}
