<?php

require_once 'Interfaces/IEnum.php';

enum RolEnum: int implements IEnum{
    case bartender = 1;
    case cervecero = 2;
    case cocinero = 3;
    case mozo = 4;
    case socio = 5;

    // Método para obtener el nombre del rol
    public function getNombre(): string {
        return match($this) {
            self::bartender => 'bartender',
            self::cervecero => 'cervecero',
            self::cocinero => 'cocinero',
            self::mozo => 'mozo',
            self::socio => 'socio',
        };
    }

    // Método para obtener el enum a partir de un id
    public static function fromId(int $id): ?self {
        return match($id) {
            1 => self::bartender,
            2 => self::cervecero,
            3 => self::cocinero,
            4 => self::mozo,
            5 => self::socio,
            default => null,
        };
    }

    public static function imprimirOpciones(): string {
        $opciones = '';
        foreach (self::cases() as $case) {
            $opciones .= $case->value . ' = ' . $case->getNombre() . ' / ';
        }
        return rtrim($opciones, ' / ');
    }
}

?>