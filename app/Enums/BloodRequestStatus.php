<?php

namespace App\Enums;

enum BloodRequestStatus: string
{
    case DRAFT = 'draft';
    case PENDING_VERIFICATION = 'pending_verification';
    case NEED_REVISION = 'need_revision';
    case ACTIVE = 'active';
    case REJECTED = 'rejected';
    case FULFILLED = 'fulfilled';
    case EXPIRED = 'expired';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draf Tersimpan',
            self::PENDING_VERIFICATION => 'Menunggu Verifikasi UDD',
            self::NEED_REVISION => 'Perlu Revisi Data',
            self::ACTIVE => 'Aktif & Dipublikasikan',
            self::REJECTED => 'Ditolak Medis',
            self::FULFILLED => 'Kebutuhan Terpenuhi',
            self::EXPIRED => 'Masa Aktif Berakhir',
            self::CANCELLED => 'Dibatalkan Pemohon',
        };
    }

    public function pillClass(): string
    {
        return match ($this) {
            self::ACTIVE, self::FULFILLED => 'dc-pill-success',
            self::PENDING_VERIFICATION, self::NEED_REVISION => 'dc-pill-warning',
            self::REJECTED, self::CANCELLED => 'dc-pill-critical',
            self::DRAFT, self::EXPIRED => 'dc-pill-neutral',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::DRAFT => 'bi-file-earmark-text',
            self::PENDING_VERIFICATION => 'bi-hourglass-split',
            self::NEED_REVISION => 'bi-pencil-square',
            self::ACTIVE => 'bi-broadcast-pin',
            self::REJECTED => 'bi-x-octagon',
            self::FULFILLED => 'bi-check2-circle',
            self::EXPIRED => 'bi-clock-history',
            self::CANCELLED => 'bi-slash-circle',
        };
    }

    public function isEditableByRequester(): bool
    {
        return in_array($this, [
            self::DRAFT,
            self::PENDING_VERIFICATION,
            self::NEED_REVISION,
        ], true);
    }

    public function isCancellableByRequester(): bool
    {
        return in_array($this, [
            self::DRAFT,
            self::PENDING_VERIFICATION,
            self::NEED_REVISION,
            self::ACTIVE,
        ], true);
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
