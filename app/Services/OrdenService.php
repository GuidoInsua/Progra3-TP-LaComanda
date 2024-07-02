<?php

require_once 'Services/AService.php';
require_once 'Enums/EstadoPedidoEnum.php';
require_once 'Models/Orden.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

class OrdenService extends AService {

    public function obtenerTodasLasOrdenes() {
        try {
            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM relacionpedidoproducto");
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
            $ordenes = [];
            foreach ($resultados as $fila) {
                $ordenes[] = new Orden($fila);
            }
            return $ordenes;
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener los pedidos pendientes: " . $e->getMessage());
        }
    }

    public function obtenerOrdenesPorEstado($parametros) {
        try {
            $estadoOrden = $parametros['estadoOrden'];
    
            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM relacionpedidoproducto WHERE estadoOrden = :estadoOrden");
            $consulta->bindParam(':estadoOrden', $estadoOrden, PDO::PARAM_INT);
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
            $ordenes = [];
            foreach ($resultados as $fila) {
                $ordenes[] = new Orden($fila);
            }
            return $ordenes;
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener los pedidos pendientes: " . $e->getMessage());
        }
    }
    

    public function obtenerOrdenesPorSector($parametros) {
        try {
            $sector = $parametros['sector'];

            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM relacionpedidoproducto WHERE sector = :sector");
            $consulta->bindParam(':sector', $sector, PDO::PARAM_STR);
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
            $ordenes = [];
            foreach ($resultados as $fila) {
                $ordenes[] = new Orden($fila);
            }
            return $ordenes;
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener los pedidos por sector: " . $e->getMessage());
        }
    }

    public function obtenerOrdenesPorEstadoSector($parametros){
        try {
            $estado = $parametros['estadoOrden'];
            $sector = $parametros['sector'];

            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM relacionpedidoproducto WHERE sector = :sector AND estadoOrden = :estadoOrden");
            $consulta->bindParam(':estadoOrden', $estado, PDO::PARAM_STR);
            $consulta->bindParam(':sector', $sector, PDO::PARAM_STR);
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
            $ordenes = [];
            foreach ($resultados as $fila) {
                $ordenes[] = new Orden($fila);
            }
            return $ordenes;
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener los pedidos por sector: " . $e->getMessage());
        }
    }

    public function modificarEstadoOrden($parametros) {
        try {
            $idPedido = $parametros['idPedido'];
            $idProducto = $parametros['idProducto'];
            $estado = $parametros['estadoOrden'];

            $consulta = $this->accesoDatos->prepararConsulta("UPDATE relacionpedidoproducto SET estadoOrden = :estado WHERE idPedido = :idPedido AND idProducto = :idProducto");
            $consulta->bindParam(':estadoOrden', $estado, PDO::PARAM_STR);
            $consulta->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);
            $consulta->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
            $consulta->execute();

        } catch (Exception $e) {
            throw new RuntimeException("Error al modificar el estado de la orden: " . $e->getMessage());
        }
    }

    public function modificarTiempoEstimadoOrden($parametros) {
        try {
            $idPedido = $parametros['idPedido'];
            $idProducto = $parametros['idProducto'];
            $tiempoEstimado = $parametros['tiempoEstimado'];

            $consulta = $this->accesoDatos->prepararConsulta("UPDATE relacionpedidoproducto SET tiempoEstimado = :tiempoEstimado WHERE idPedido = :idPedido AND idProducto = :idProducto");
            $consulta->bindParam(':tiempoEstimado', $tiempoEstimado, PDO::PARAM_STR);
            $consulta->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);
            $consulta->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
            $consulta->execute();

        } catch (Exception $e) {
            throw new RuntimeException("Error al modificar el estado de la orden: " . $e->getMessage());
        }
    }

    public function modificarIdUsuarioOrden($parametros) {
        try {
            $idPedido = $parametros['idPedido'];
            $idProducto = $parametros['idProducto'];
            $idUsuario = $parametros['idUsuario'];

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