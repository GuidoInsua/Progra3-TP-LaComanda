<?php

require_once 'Models/Pedido.php';
require_once 'Services/AService.php';
require_once 'Enums/EstadoPedidoEnum.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

class PedidoService extends AService {

    public function altaPedido($parametros) {
        try {
            $pedido = new Pedido($parametros);
            $pedidoExistente = $this->verificarPedidoExistente($pedido->getCodigo());

            if ($pedidoExistente) {
                $mensaje = "El pedido ya existe";
            } else {
                $this->GenerarDatosBasicosPedido($pedido);
                $this->registrarNuevoPedido($pedido);
                $mensaje = "Pedido dado de alta exitosamente";
            }

            return $mensaje;
        } catch (Exception $e) {
            throw new RuntimeException("Error al dar de alta el pedido: " . $e->getMessage());
        }
    }

    public function obtenerTodosLosPedidos(): array{
        try {
            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM pedido");
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

            $pedidos = [];

            foreach ($resultados as $fila) {
                $pedidos[] = new Pedido($fila);
            }

            return $pedidos;
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener los pedidos: " . $e->getMessage());
        }
    }

    public function obtenerUnPedido($parametros): ?Pedido {
        try {
            $pedidoExistente = $this->verificarPedidoExistente($parametros['codigo']);

            return $pedidoExistente;
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener el pedido: " . $e->getMessage());
        }
    }

    public function modificarPedido($parametros) {
        try {
            $pedidoExistente = $this->verificarPedidoExistente($parametros['codigo']);

            if ($pedidoExistente) {
                $this->actualizarEstadoPedido($parametros);
                $mensaje = "Pedido actualizado exitosamente, paso de estado " . $pedidoExistente['estadoPedido'] . " a " . $parametros['estadoPedido'];
            } else {
                $mensaje = "El pedido no existe";
            }

            return $mensaje;
        } catch (Exception $e) {
            throw new RuntimeException("Error al modificar el pedido: " . $e->getMessage());
        }
    }

    public function bajaPedido($parametros) {
        try {
            $pedidoExistente = $this->verificarPedidoExistente($parametros['codigo']);

            if ($pedidoExistente) {
                $parametros['estadoPedido'] = EstadoPedidoEnum::Cancelado->value;    
                $parametros['fechaBaja'] = date('Y-m-d'); 
                $this->darDeBajaPedido($parametros);
                $mensaje = "Pedido dado de baja exitosamente";
            } else {
                $mensaje = "El pedido no existe";
            }

            return $mensaje;
        } catch (Exception $e) {
            throw new RuntimeException("Error al dar de baja el pedido: " . $e->getMessage());
        }
    }

    private function verificarPedidoExistente($codigo) {
        try {
            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM pedido WHERE codigo = :codigo");
            $consulta->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                return new Pedido($resultado);
            } else {
                return null;
            }
        } catch (Exception $e) {
            throw new RuntimeException("Error al verificar el pedido: " . $e->getMessage());
        }
    }

    private function registrarNuevoPedido($pedido) {
        try {

            $codigo = $pedido->getCodigo();
            $nombreCliente = $pedido->getNombreCliente();
            $idMesa = $pedido->getIdMesa();
            $estadoPedido = $pedido->getEstadoPedido();
            $fechaCreacion = $pedido->getFechaCreacion();

            $consulta = $this->accesoDatos->prepararConsulta("
            INSERT INTO pedido (codigo, nombreCliente, idMesa, estadoPedido, fechaCreacion) 
            VALUES (:codigo, :nombreCliente, :idMesa, :estadoPedido, :fechaCreacion)
            ");
            $consulta->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $consulta->bindParam(':nombreCliente', $nombreCliente, PDO::PARAM_STR);
            $consulta->bindParam(':idMesa', $idMesa, PDO::PARAM_INT);
            $consulta->bindParam(':estadoPedido', $estadoPedido, PDO::PARAM_INT);
            $consulta->bindParam(':fechaCreacion', $fechaCreacion, PDO::PARAM_STR);
            $consulta->execute();
        } catch (Exception $e) {
            throw new RuntimeException("Error al registrar el nuevo pedido: " . $e->getMessage());
        }
    }

    public function GenerarDatosBasicosPedido($pedido) {
        try {
            do {
                // Generar una cadena hexadecimal de 10 caracteres
                $codigoUnico = substr(bin2hex(random_bytes(5)), 0, 10);
            } while ($this->verificarPedidoExistente($codigoUnico));
    
            $fecha = date('Y-m-d H:i:s');
    
            $pedido->setCodigo($codigoUnico);
            $pedido->setFechaCreacion($fecha);
        } catch (Exception $e) {
            throw new RuntimeException("Error al generar los datos básicos del pedido: " . $e->getMessage());
        }
    }

    private function actualizarEstadoPedido($parametros) {
        try {

            $estadoPedido = $parametros['estadoPedido'];
            $codigo = $parametros['codigo'];

            $actualizacion = $this->accesoDatos->prepararConsulta("UPDATE pedido SET estadoPedido = :estadoPedido WHERE codigo = :codigo");
            $actualizacion->bindParam(':estadoPedido', $estadoPedido, PDO::PARAM_INT);
            $actualizacion->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $actualizacion->execute();
        } catch (Exception $e) {
            throw new RuntimeException("Error al actualizar el estado del pedido: " . $e->getMessage());
        }
    }

    private function darDeBajaPedido($parametros) {
        try {

            $estadoPedido = $parametros['estadoPedido'];
            $fechaBaja = $parametros['fechaBaja'];
            $codigo = $parametros['codigo'];

            $actualizacion = $this->accesoDatos->prepararConsulta("UPDATE pedido SET estadoPedido = :estadoPedido, fechaBaja = :fechaBaja WHERE codigo = :codigo");
            $actualizacion->bindParam(':estadoPedido', $estadoPedido, PDO::PARAM_INT);
            $actualizacion->bindParam(':fechaBaja', $fechaBaja, PDO::PARAM_STR);
            $actualizacion->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $actualizacion->execute();
        } catch (Exception $e) {
            throw new RuntimeException("Error al dar de baja el pedido: " . $e->getMessage());
        }
    }
}

?>