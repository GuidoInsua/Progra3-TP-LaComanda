<?php

require_once 'Models/Producto.php';
require_once 'Services/AService.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

class ProductoService extends AService {

    public function altaProducto($parametros) {
        try {
            $producto = new Producto($parametros);
            $productoExistente = $this->verificarProductoExistente($producto->getTipo(), $producto->getIdSector());
            if ($productoExistente) {
                $mensaje = "El producto ya existe";
            } else {
                $this->registrarNuevoProducto($producto);
                $mensaje = "Producto dado de alta exitosamente";
            }
            return $mensaje;
        } catch (Exception $e) {
            throw new RuntimeException("Error al dar de alta el producto: " . $e->getMessage());
        }
    }

    public function obtenerTodosLosProductos(): array{
        try {
            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM producto");
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

            $productos = [];

            foreach ($resultados as $fila) {
                $productos[] = new Producto($fila);
            }
            return $productos;
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener los productos: " . $e->getMessage());
        }
    }

    public function obtenerUnProducto($parametros): ?Producto {
        try {
            $productoExistente = $this->verificarProductoExistente($parametros['tipo'], $parametros['idSector']);

            return $productoExistente;
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener el producto: " . $e->getMessage());
        }
    }

    public function modificarProducto($parametros) {
        try {
            $productoExistente = $this->verificarProductoExistente($parametros['tipo'], $parametros['idSector']);

            if ($productoExistente) {
                $this->actualizarPrecioProducto($parametros);
                $mensaje = "Producto actualizado exitosamente, paso de precio " . $productoExistente['precio'] . " a " . $parametros['precio'];
            } else {
                $mensaje = "El producto no existe";
            }

            return $mensaje;
        } catch (Exception $e) {
            throw new RuntimeException("Error al modificar el producto: " . $e->getMessage());
        }
    }

    public function bajaProducto($parametros) {
        try {
            $productoExistente = $this->verificarProductoExistente($parametros['tipo'], $parametros['idSector']);

            if ($productoExistente) {
                $fecha = date('Y-m-d');
                $parametros['fechaBaja'] = $fecha;
                $this->actualizarFechaBajaProducto($parametros);
                $mensaje = "Producto dado de baja exitosamente";
            } else {
                $mensaje = "El producto no existe";
            }

            return $mensaje;
        } catch (Exception $e) {
            throw new RuntimeException("Error al dar de baja el producto: " . $e->getMessage());
        }
    }

    private function verificarProductoExistente($tipo, $idSector) {
        try {
            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM producto WHERE tipo = :tipo AND idSector = :idSector");
            $consulta->bindParam(':tipo', $tipo, PDO::PARAM_STR);
            $consulta->bindParam(':idSector', $idSector, PDO::PARAM_INT);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                return new Producto($resultado);
            } else {
                return null;
            }
        } catch (Exception $e) {
            throw new RuntimeException("Error al verificar la existencia del producto: " . $e->getMessage());
        }
    }

    public function obtenerProductoPorId($id) {
        try {
            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM producto WHERE id = :id");
            $consulta->bindParam(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                return new Producto($resultado);
            } else {
                return null;
            }
        } catch (Exception $e) {
            throw new RuntimeException("Error al verificar la existencia del producto: " . $e->getMessage());
        }
    }

    private function registrarNuevoProducto($producto) {
        try {

            $precio = $producto->getPrecio();
            $tipo = $producto->getTipo();
            $idSector = $producto->getIdSector();

            $consulta = $this->accesoDatos->prepararConsulta("INSERT INTO producto (precio, tipo, idSector) VALUES (:precio, :tipo, :idSector)");
            $consulta->bindParam(':precio', $precio, PDO::PARAM_INT);
            $consulta->bindParam(':tipo', $tipo, PDO::PARAM_STR);
            $consulta->bindParam(':idSector', $idSector, PDO::PARAM_INT);
            $consulta->execute();
        } catch (Exception $e) {
            throw new RuntimeException("Error al registrar el producto: " . $e->getMessage());
       }
    }

    private function actualizarPrecioProducto($parametros) {
        try {

            $precio = $parametros['precio'];
            $tipo = $parametros['tipo'];
            $idSector = $parametros['idSector'];

            $consulta = $this->accesoDatos->prepararConsulta("UPDATE producto SET precio = :precio WHERE tipo = :tipo AND idSector = :idSector");
            $consulta->bindParam(':precio', $precio, PDO::PARAM_STR);
            $consulta->bindParam(':tipo', $tipo, PDO::PARAM_STR);
            $consulta->bindParam(':idSector', $idSector, PDO::PARAM_INT);
            $consulta->execute();
        } catch (Exception $e) {
            throw new RuntimeException("Error al actualizar el precio del producto: " . $e->getMessage());
        }
    }

    private function actualizarFechaBajaProducto($parametros) {
        try {
            $fechaBaja = $parametros['fechaBaja'];
            $tipo = $parametros['tipo'];
            $idSector = $parametros['idSector'];

            $consulta = $this->accesoDatos->prepararConsulta("UPDATE producto SET fechaBaja = :fechaBaja WHERE tipo = :tipo AND idSector = :idSector");
            $consulta->bindParam(':fechaBaja', $fechaBaja, PDO::PARAM_STR);
            $consulta->bindParam(':tipo', $tipo, PDO::PARAM_STR);
            $consulta->bindParam(':idSector', $idSector, PDO::PARAM_INT);
            $consulta->execute();
        } catch (Exception $e) {
            throw new RuntimeException("Error al actualizar la fecha de baja del producto: " . $e->getMessage());
        }
    }
    
}

?>