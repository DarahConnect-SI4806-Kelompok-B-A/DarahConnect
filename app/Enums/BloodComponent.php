<?php

namespace App\Enums;

enum BloodComponent: string
{
    case PRC = 'PRC';
    case WB = 'WB';
    case TC = 'TC';
    case FFP = 'FFP';

    public function label(): string
    {
        return match ($this) {
            self::PRC => 'Packed Red Cells (PRC)',
            self::WB => 'Whole Blood (WB)',
            self::TC => 'Thrombocyte Concentrate (TC)',
            self::FFP => 'Fresh Frozen Plasma (FFP)',
        };
    }

    public function clinicalHint(): string
    {
        return match ($this) {
            self::PRC => 'Sel darah merah pekat untuk anemia berat atau kadar Hb rendah.',
            self::WB => 'Darah lengkap untuk pendarahan masif akut atau tindakan bedah besar.',
            self::TC => 'Konsentrat trombosit untuk DBD atau gangguan trombositopenia.',
            self::FFP => 'Plasma segar beku untuk gangguan faktor pembekuan darah.',
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
