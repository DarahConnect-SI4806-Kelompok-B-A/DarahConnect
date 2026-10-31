<?php

namespace App\Enums;

enum UserRole: string
{
    case PENCARI_DARAH = 'pencari_darah';
    case PENDONOR = 'pendonor';
    case PETUGAS_UDD = 'petugas_udd';
    case PETUGAS_RS = 'petugas_rs';
    case ADMIN = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::PENCARI_DARAH => 'Pencari Darah',
            self::PENDONOR => 'Pendonor',
            self::PETUGAS_UDD => 'Petugas UDD PMI',
            self::PETUGAS_RS => 'Petugas BDRS / Rumah Sakit',
            self::ADMIN => 'Administrator Sistem',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::PENCARI_DARAH => 'Keluarga atau perwakilan pasien yang mengajukan kebutuhan transfusi darah darurat dan berkoordinasi di rumah sakit.',
            self::PENDONOR => 'Relawan masyarakat yang siap merespons kebutuhan darah darurat maupun memesan jadwal donor sukarela di UDD/Mobile Unit.',
            self::PETUGAS_UDD => 'Petugas operasional dan laboratorium Unit Donor Darah PMI.',
            self::PETUGAS_RS => 'Petugas Bank Darah Rumah Sakit (BDRS) mitra rujukan.',
            self::ADMIN => 'Pengelola teknis dan konfigurasi master sistem DarahConnect.',
        };
    }

    /**
     * Roles permitted during public self-registration.
     *
     * @return array<int, self>
     */
    public static function publicRegistrationRoles(): array
    {
        return [
            self::PENCARI_DARAH,
            self::PENDONOR,
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function publicRegistrationValues(): array
    {
        return array_map(
            static fn (self $role): string => $role->value,
            self::publicRegistrationRoles()
        );
    }
}
