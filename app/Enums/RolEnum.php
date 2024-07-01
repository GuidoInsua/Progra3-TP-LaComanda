<?php

enum RolEnum: int {
    case Bartender = 1;
    case Cervecero = 2;
    case Cocinero = 3;
    case Mozo = 4;
    case Socio = 5;

    // Método para obtener el nombre del rol
    public function getNombre(): string {
        return match($this) {
            self::Bartender => 'Bartender',
            self::Cervecero => 'Cervecero',
            self::Cocinero => 'Cocinero',
            self::Mozo => 'Mozo',
            self::Socio => 'Socio',
        };
    }

    // Método para obtener el enum a partir de un id
    public static function fromId(int $id): ?self {
        return match($id) {
            1 => self::Bartender,
            2 => self::Cervecero,
            3 => self::Cocinero,
            4 => self::Mozo,
            5 => self::Socio,
            default => null,
        };
    }
}

?>