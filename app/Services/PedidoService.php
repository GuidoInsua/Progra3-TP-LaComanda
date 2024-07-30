<?php

require_once 'Models/Pedido.php';
require_once 'Models/Producto.php';
require_once 'Services/AService.php';
require_once 'Enums/EstadoPedidoEnum.php';
require_once 'Services/ProductoService.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

class PedidoService extends AService {

    public function altaPedido($parametros) {
        //entiendo que una mesa puede tener varios pedidos, pido comida despues poste (por eso no valido que exista pedido)
        try {
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
            
            return $mensaje;
        } catch (Exception $e) {
            throw new RuntimeException("Error al dar de alta el pedido: " . $e->getMessage());
        }
    }

    public function obtenerTodosLosPedidos(): array{
        try {
            $this->calcularTiempoEstimadoTodosLosProductos();    
            $this->calcularPrecioFinalTodosLosPedidos();

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
            $this->calcularTiempoEstimadoTodosLosProductos();    
            $this->calcularPrecioFinalTodosLosPedidos();
            $this->actualizarPedidosEnListoParaServir();

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
                    $estadoOrden = EstadoPedidoEnum::Pendiente->value;  

                    $consulta = $this->accesoDatos->prepararConsulta("
                    INSERT INTO relacionpedidoproducto (idPedido, idProducto, estadoOrden) 
                    VALUES (:idPedido, :idProducto, :estadoOrden)
                    ");
                    $consulta->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);
                    $consulta->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
                    $consulta->bindParam(':estadoOrden', $estadoOrden, PDO::PARAM_INT);
                    $consulta->execute();
                }
            }
        } catch (Exception $e) {
            throw new RuntimeException("Error al cargar la relación pedido-producto: " . $e->getMessage());
        }
    }

    public function actualizarEstadoPedido($parametros) {
        try {
            $estadoPedido = $parametros['estadoPedido'];
            $codigo = $parametros['codigo'];

            $actualizacion = $this->accesoDatos->prepararConsulta("UPDATE pedido SET estadoPedido = :estadoPedido WHERE codigo = :codigo");
            $actualizacion->bindParam(':estadoPedido', $estadoPedido, PDO::PARAM_INT);
            $actualizacion->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $actualizacion->execute();

            if($estadoPedido == EstadoPedidoEnum::Entregado->value){
                $fechaBaja = date('Y-m-d H:i:s');
                $actualizacion = $this->accesoDatos->prepararConsulta("UPDATE pedido SET fechaBaja = :fechaBaja WHERE codigo = :codigo");
                $actualizacion->bindParam(':fechaBaja', $fechaBaja, PDO::PARAM_STR);
                $actualizacion->bindParam(':codigo', $codigo, PDO::PARAM_STR);
                $actualizacion->execute();
            }
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

    public function obtenerTiempoEstimadoPorMesa($parametros)
    {
        try {
            $idMesa = $parametros['idMesa'];
            $codigo = $parametros['codigo'];

            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM pedido WHERE idMesa = :idMesa AND codigo = :codigo");
            $consulta->bindParam(':idMesa', $idMesa, PDO::PARAM_INT);
            $consulta->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                $pedido = new Pedido($resultado);

                $ordenServie = new OrdenService();
                $tiempoEstimado = $ordenServie->obtenerMaximioTiempoOrdenPorPedido($pedido->getId());

                $this->actualizarTiempoEstimadoPedido($pedido->getId(), $tiempoEstimado);

                if($tiempoEstimado != null) {
                $mensaje = "Tiempo estimado para la mesa: " . $idMesa . " es de " . $tiempoEstimado;
                }
                else {
                    $mensaje = "No se le asigno un tiempo al pedido";
                }
            } else {
                $mensaje = "No se encontraron pedidos para la mesa";
            }

            return $mensaje;
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener el tiempo estimado por mesa: " . $e->getMessage());
        }
    }

    public function actualizarTiempoEstimadoPedido($idPedido, $tiempoEstimado) {
        try {
            $actualizacion = $this->accesoDatos->prepararConsulta("UPDATE pedido SET tiempoEstimado = :tiempoEstimado WHERE id = :id");
            $actualizacion->bindParam(':tiempoEstimado', $tiempoEstimado, PDO::PARAM_INT);
            $actualizacion->bindParam(':id', $idPedido, PDO::PARAM_INT);
            $actualizacion->execute();
        } catch (Exception $e) {
            throw new RuntimeException("Error al actualizar el tiempo estimado del pedido: " . $e->getMessage());
        }
    }

    public function calcularTiempoEstimadoTodosLosProductos()
    {
        try {
            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM pedido");
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultados as $fila) {
                $pedido = new Pedido($fila);

                $ordenServie = new OrdenService();
                $tiempoEstimado = $ordenServie->obtenerMaximioTiempoOrdenPorPedido($pedido->getId());

                $this->actualizarTiempoEstimadoPedido($pedido->getId(), $tiempoEstimado);
            }

            $mensaje = "Tiempo estimado para todos los pedidos actualizado exitosamente";
            return $mensaje;
        } catch (Exception $e) {
            throw new RuntimeException("Error al calcular el tiempo estimado de todos los productos: " . $e->getMessage());
        }
    }

    public function calcularPrecioFinalTodosLosPedidos()
    {
        try {
            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM pedido");
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultados as $fila) {
                $pedido = new Pedido($fila);

                $productos = $this->obtenerProductosDelPedido($pedido->getId());

                $precioFinal = 0;

                foreach ($productos as $producto) {
                    $precioFinal += $producto->getPrecio();
                }

                $this->actualizarPrecioFinalPedido($pedido->getId(), $precioFinal);
            }

            $mensaje = "Precio final para todos los pedidos actualizado exitosamente";
            return $mensaje;
        } catch (Exception $e) {
            throw new RuntimeException("Error al calcular el precio final de todos los pedidos: " . $e->getMessage());
        }
    }

    public function actualizarPrecioFinalPedido($idPedido, $precioFinal) {
        try {
            $actualizacion = $this->accesoDatos->prepararConsulta("UPDATE pedido SET precioFinal = :precioFinal WHERE id = :id");
            $actualizacion->bindParam(':precioFinal', $precioFinal, PDO::PARAM_INT);
            $actualizacion->bindParam(':id', $idPedido, PDO::PARAM_INT);
            $actualizacion->execute();
        } catch (Exception $e) {
            throw new RuntimeException("Error al actualizar el precio final del pedido: " . $e->getMessage());
        }
    }

    public function obtenerPedidosParaServir() {
        try {
            $this->calcularTiempoEstimadoTodosLosProductos();    
            $this->calcularPrecioFinalTodosLosPedidos();
            $this->actualizarPedidosEnListoParaServir();

            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM pedido WHERE estadoPedido = 3");
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

            if($resultados != null) {
                return $pedidos;
            }
            else
            {
                return NULL;
            }
            
            
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener los pedidos listos para servir: " . $e->getMessage());
        }
    }

    public function actualizarPedidosEnListoParaServir() {
        try {
            $pedidos = $this->obtenerTodosLosPedidos();
            $ordenServie = new OrdenService();

            foreach($pedidos as $pedido) {
                $ordenes = $ordenServie->obtenerOrdenesPorPedido($pedido->getId());

                $todosListos = true;
                foreach($ordenes as $orden) {
                    if($orden->getEstadoOrden() != EstadoPedidoEnum::ListoParaServir->value) {
                        $todosListos = false;
                    }
                }

                if($todosListos) {
                    $this->actualizarEstadoPedido(['codigo' => $pedido->getCodigo(), 'estadoPedido' => EstadoPedidoEnum::ListoParaServir->value]);
                }
            }
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener los pedidos listos para servir: " . $e->getMessage());
        }
    }

    public function obtenerPedidosFueraDeTiempo()
    {
        try {
            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM pedido WHERE estadoPedido = 5");
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultados as $fila) {
                $pedido = new Pedido($fila);

                $tiempoEstimado = $pedido->getTiempoEstimado();
                $fechaCreacion = $pedido->getFechaCreacion();
                $fechaBaja = $pedido->getFechaBaja();

                $fechaCreacion = new DateTime($fechaCreacion);
                $fechaBaja = new DateTime($fechaBaja);

                $interval = $fechaCreacion->diff($fechaBaja);

                if($interval->format('%i') > $tiempoEstimado) {
                    $pedidos[] = $pedido;
                }
            }

            if($resultados != null) {
                return $pedidos;
            }
            else
            {
                return NULL;
            }
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener los pedidos fuera de tiempo: " . $e->getMessage());
        }
    }

}

?>