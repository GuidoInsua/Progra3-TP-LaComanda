<?php

enum SectorEnum: int {
    case TragosYVinos = 1;
    case Choperas = 2;
    case Cocina = 3;
    case CandyBar = 4;

    public function getNombre(): string {
        return match($this) {
            self::TragosYVinos => 'Tragos y Vinos',
            self::Choperas => 'Choperas',
            self::Cocina => 'Cocina',
            self::CandyBar => 'Candy Bar',
        };
    }

    public static function fromId(int $id): ?self {
        return match($id) {
            1 => self::TragosYVinos,
            2 => self::Choperas,
            3 => self::Cocina,
            4 => self::CandyBar,
            default => null,
        };
    }
}

?>