<?php

require_once 'Models/Producto.php';
require_once 'Services/AService.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

class ProductoService extends AService {

    //obtiene tipo, sector y precio
    public function altaProducto($parametros) {
        try {
            $productoExistente = $this->obtenerProductoPorTipo($parametros);
            if ($productoExistente) {
                $mensaje = "El producto ya existe";
            } else {
                $producto = new Producto($parametros);
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

    public function obtenerProductoPorTipo($parametros): ?Producto {
        try {
            $tipo = $parametros['tipo'];

            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM producto WHERE tipo = :tipo");
            $consulta->bindParam(':tipo', $tipo, PDO::PARAM_INT);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                return new Producto($resultado);
            } else {
                return null;
            }
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener el producto: " . $e->getMessage());
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
            throw new RuntimeException("Error al obtener el producto: " . $e->getMessage());
        }
    }

    public function obtenerTodosLosProductosPorSector($idSector) {
        try {
            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM producto WHERE idSector = :idSector");
            $consulta->bindParam(':idSector', $idSector, PDO::PARAM_INT);
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

            $productos = [];

            foreach ($resultados as $fila) {
                $producto = new Producto($fila);
                $productos[] = $producto;
            }
            return $productos;
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener los productos por sector: " . $e->getMessage());
        }
    }

    public function modificarProducto($parametros) {
        try {
            $productoExistente = $this->obtenerProductoPorTipo($parametros['tipo']);

            if ($productoExistente) {
                $this->actualizarPrecioProducto($productoExistente);
                $mensaje = "Producto actualizado exitosamente";
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
            $productoExistente = $this->obtenerProductoPorTipo($parametros['tipo']);

            if ($productoExistente) {
                $fechaBaja = date('Y-m-d');
                $this->actualizarFechaBajaProducto($productoExistente, $fechaBaja);
                $mensaje = "Producto dado de baja exitosamente";
            } else {
                $mensaje = "El producto no existe";
            }

            return $mensaje;
        } catch (Exception $e) {
            throw new RuntimeException("Error al dar de baja el producto: " . $e->getMessage());
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

    private function actualizarPrecioProducto($producto) {
        try {
            $id = $producto->getId();
            $precio = $producto->getPrecio();

            $consulta = $this->accesoDatos->prepararConsulta("UPDATE producto SET precio = :precio WHERE id = :id");
            $consulta->bindParam(':id', $id, PDO::PARAM_INT);
            $consulta->bindParam(':precio', $precio, PDO::PARAM_STR);
            $consulta->execute();
        } catch (Exception $e) {
            throw new RuntimeException("Error al actualizar el precio del producto: " . $e->getMessage());
        }
    }

    private function actualizarFechaBajaProducto($productoExistente, $fechaBaja) {
        try {
            $id = $productoExistente->getId();

            $consulta = $this->accesoDatos->prepararConsulta("UPDATE producto SET fechaBaja = :fechaBaja WHERE id = :id");
            $consulta->bindParam(':id', $id, PDO::PARAM_INT);
            $consulta->bindParam(':fechaBaja', $fechaBaja, PDO::PARAM_STR);
            $consulta->execute();
        } catch (Exception $e) {
            throw new RuntimeException("Error al actualizar la fecha de baja del producto: " . $e->getMessage());
        }
    }
    
}

?>