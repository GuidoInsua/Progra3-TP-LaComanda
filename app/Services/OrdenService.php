<?php

require_once 'Services/AService.php';
require_once 'Enums/EstadoPedidoEnum.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

class OrdenService extends AService {

    public function obtenerOrdenesPorEstado($estado) {
        try {
            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM relacionpedidoproducto WHERE estado = :estado");
            $consulta->bindParam(':estado', $estado, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener los pedidos pendientes: " . $e->getMessage());
        }
    }

    public function obtenerOrdenesPorSector($sector) {
        try {
            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM relacionpedidoproducto WHERE sector = :sector");
            $consulta->bindParam(':sector', $sector, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener los pedidos por sector: " . $e->getMessage());
        }
    }

    public function modificarEstadoOrden($idPedido, $idProducto, $estado) {
        try {
            $consulta = $this->accesoDatos->prepararConsulta("UPDATE relacionpedidoproducto SET estado = :estado WHERE idPedido = :idPedido AND idProducto = :idProducto");
            $consulta->bindParam(':estado', $estado, PDO::PARAM_STR);
            $consulta->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);
            $consulta->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
            $consulta->execute();
        } catch (Exception $e) {
            throw new RuntimeException("Error al modificar el estado de la orden: " . $e->getMessage());
        }
    }

    public function modificarTiempoEstimadoOrden($idPedido, $idProducto, $tiempoEstimado) {
        try {
            $consulta = $this->accesoDatos->prepararConsulta("UPDATE relacionpedidoproducto SET tiempoEstimado = :tiempoEstimado WHERE idPedido = :idPedido AND idProducto = :idProducto");
            $consulta->bindParam(':tiempoEstimado', $tiempoEstimado, PDO::PARAM_STR);
            $consulta->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);
            $consulta->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
            $consulta->execute();
        } catch (Exception $e) {
            throw new RuntimeException("Error al modificar el estado de la orden: " . $e->getMessage());
        }
    }

    public function modificarIdUsuarioOrden($idPedido, $idProducto, $idUsuario) {
        try {
            $consulta = $this->accesoDatos->prepararConsulta("UPDATE relacionpedidoproducto SET idUsuario = :idUsuario WHERE idPedido = :idPedido AND idProducto = :idProducto");
            $consulta->bindParam(':idUsuario', $idUsuario, PDO::PARAM_STR);
            $consulta->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);
            $consulta->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
            $consulta->execute();
        } catch (Exception $e) {
            throw new RuntimeException("Error al modificar el estado de la orden: " . $e->getMessage());
        }
    }

}