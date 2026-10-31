<?php

namespace App\Enums;

enum Rhesus: string
{
    case POSITIVE = '+';
    case NEGATIVE = '-';

    public function label(): string
    {
        return match ($this) {
            self::POSITIVE => 'Positif (+)',
            self::NEGATIVE => 'Negatif (-)',
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
