<?php

enum EstadoMesaEnum: int {
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
}

?>
