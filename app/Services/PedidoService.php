<?php

require_once 'Models/Pedido.php';
require_once 'Models/Producto.php';
require_once 'Services/AService.php';
require_once 'Enums/EstadoPedidoEnum.php';
require_once 'Services/ProductoService.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

class PedidoService extends AService {

    public function altaPedido($parametros) {
        try {
            $pedidoExistente = $this->obtenerPedidoBasePorCodigo($parametros['codigo']);

            if ($pedidoExistente) {
                $mensaje = "El pedido ya existe";
            } else {
                $datosPedido = [
                    'nombreCliente' => $parametros['nombreCliente'],
                    'idMesa' => $parametros['idMesa'],
                ];
    
                $productos = $parametros['productos'];
        
                $pedido = new Pedido($datosPedido);

                $this->GenerarDatosBasicosPedido($pedido);
                $idPedido = $this->registrarNuevoPedido($pedido);
                $this->altaRelacionPedidoProducto($idPedido, $productos);
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

            foreach ($resultados as $fila) {
                $pedido = new Pedido($fila);

                $productos = $this->obtenerProductosDelPedido($pedido->getId());

                foreach ($productos as $producto) {
                    $pedido->addProducto($producto);
                }

                $pedidos[] = $pedido;
            }

            return $pedidos;
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener los pedidos: " . $e->getMessage());
        }
    }

    public function obtenerUnPedido($parametros): ?Pedido {
        try {
            $pedidoExistente = $this->obtenerPedidoBasePorCodigo($parametros['codigo']);

            $productos = $this->obtenerProductosDelPedido($pedidoExistente->getId());

            foreach ($productos as $producto) {
                $pedidoExistente->addProducto($producto);
            }

            return $pedidoExistente;
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener el pedido: " . $e->getMessage());
        }
    }

    public function modificarPedido($parametros) {
        try {
            $pedidoExistente = $this->obtenerPedidoBasePorCodigo($parametros['codigo']);

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
            $pedidoExistente = $this->obtenerPedidoBasePorCodigo($parametros['codigo']);

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

    private function obtenerPedidoBasePorCodigo($codigo) {
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

            $ultimoId = $this->accesoDatos->obtenerUltimoId(); 
            return $ultimoId;
        } catch (Exception $e) {
            throw new RuntimeException("Error al registrar el nuevo pedido: " . $e->getMessage());
        }
    }

    public function GenerarDatosBasicosPedido($pedido) {
        try {
            do {
                // Generar una cadena hexadecimal de 10 caracteres
                $codigoUnico = substr(bin2hex(random_bytes(5)), 0, 10);
            } while ($this->obtenerPedidoBasePorCodigo($codigoUnico));
    
            $fecha = date('Y-m-d H:i:s');
    
            $pedido->setCodigo($codigoUnico);
            $pedido->setFechaCreacion($fecha);
        } catch (Exception $e) {
            throw new RuntimeException("Error al generar los datos básicos del pedido: " . $e->getMessage());
        }
    }

    public function altaRelacionPedidoProducto($idPedido, $productos) {
        try {
            $miProductoService = new ProductoService();
            foreach ($productos as $producto) {
                if($nuevoProducto = $miProductoService->obtenerProductoPorTipo($producto)) {

                    $idProducto = $nuevoProducto->getId();
                    $estadoRelacion = EstadoPedidoEnum::Pendiente->value;  

                    $consulta = $this->accesoDatos->prepararConsulta("
                    INSERT INTO relacionpedidoproducto (idPedido, idProducto, estadoRelacion) 
                    VALUES (:idPedido, :idProducto, :estadoRelacion)
                    ");
                    $consulta->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);
                    $consulta->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
                    $consulta->bindParam(':estadoRelacion', $estadoRelacion, PDO::PARAM_INT);
                    $consulta->execute();
                }
            }
        } catch (Exception $e) {
            throw new RuntimeException("Error al cargar la relación pedido-producto: " . $e->getMessage());
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

    public function obtenerProductosDelPedido($idPedido) {
        try {
            $miProductoService = new ProductoService();

            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM relacionpedidoproducto WHERE idPedido = :idPedido");
            $consulta->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultados as $fila) {
                $productos[] = $miProductoService->obtenerProductoPorId($fila['idProducto']);
            }

            return $productos;
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener los productos del pedido: " . $e->getMessage());
        }
    }


}

?>