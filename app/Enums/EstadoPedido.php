<?php


enum EstadoPedido: string {
    case Pendiente = "Pendiente";
    case EnPreparacion = "EnPreparacion";
    case ListoParaServir = "ListoParaServir";
}

?>