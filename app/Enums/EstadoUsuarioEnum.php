<?php

enum EstadoUsuarioEnum: int {
    case Activo = 1;
    case Inactivo = 2;

    public function getNombre(): string {
        return match($this) {
            self::Activo => 'Activo',
            self::Inactivo => 'Inactivo',
        };
    }

    public static function fromId(int $id): ?self {
        return match($id) {
            1 => self::Activo,
            2 => self::Inactivo,
            default => null,
        };
    }
}

?>
