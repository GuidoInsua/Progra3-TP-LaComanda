<?php

require_once 'Interfaces/IEnum.php';

enum EstadoPedidoEnum: int implements IEnum{
    case Pendiente = 1;
    case EnPreparacion = 2;
    case ListoParaServir = 3;
    case Cancelado = 4;

    public function getNombre(): string {
        return match($this) {
            self::Pendiente => 'Pendiente',
            self::EnPreparacion => 'En Preparación',
            self::ListoParaServir => 'Listo Para Servir',
            self::Cancelado => 'Cancelado',
        };
    }

    public static function fromId(int $id): ?self {
        return match($id) {
            1 => self::Pendiente,
            2 => self::EnPreparacion,
            3 => self::ListoParaServir,
            4 => self::Cancelado,
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
