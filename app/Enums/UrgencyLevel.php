<?php

namespace App\Enums;

enum UrgencyLevel: string
{
    case CRITICAL = 'critical';
    case URGENT = 'urgent';
    case STANDARD = 'standard';

    public function label(): string
    {
        return match ($this) {
            self::CRITICAL => 'Darurat Kritis (< 6 Jam)',
            self::URGENT => 'Mendesak (< 24 Jam)',
            self::STANDARD => 'Terencana (< 48 Jam)',
        };
    }

    public function shortLabel(): string
    {
        return match ($this) {
            self::CRITICAL => 'Kritis',
            self::URGENT => 'Mendesak',
            self::STANDARD => 'Terencana',
        };
    }

    public function pillClass(): string
    {
        return match ($this) {
            self::CRITICAL => 'dc-pill-critical',
            self::URGENT => 'dc-pill-warning',
            self::STANDARD => 'dc-pill-neutral',
        };
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
