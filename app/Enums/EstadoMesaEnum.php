<?php

require_once 'Interfaces/IEnum.php';

enum EstadoMesaEnum: int implements IEnum{
    case EsperandoPedido = 1;
    case Comiendo = 2;
    case Pagando = 3;
    case Cerrada = 4;
    case Baja = 5;

    public function getNombre(): string {
        return match($this) {
            self::EsperandoPedido => 'Esperando Pedido',
            self::Comiendo => 'Comiendo',
            self::Pagando => 'Pagando',
            self::Cerrada => 'Cerrada',
            self::Baja => 'Baja',
        };
    }

    public static function fromId(int $id): ?self {
        return match($id) {
            1 => self::EsperandoPedido,
            2 => self::Comiendo,
            3 => self::Pagando,
            4 => self::Cerrada,
            5 => self::Baja,
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
