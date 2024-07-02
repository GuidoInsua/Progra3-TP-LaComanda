<?php

require_once 'Models/Mesa.php';
require_once 'Services/AService.php';
require_once 'Enums/EstadoMesaEnum.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

class MesaService extends AService {

    public function altaMesa($parametros) {
        try {
            $mesaExistente = $this->obtenerMesaPorCodigo($parametros);

            if ($mesaExistente) {
                $mensaje = "La mesa ya existe";
            } else {
                $mesa = new Mesa($parametros);
                $this->registrarNuevaMesa($mesa);
                $mensaje = "Mesa dada de alta exitosamente";
            }

            return $mensaje;
        } catch (Exception $e) {
            throw new RuntimeException("Error al dar de alta la mesa: " . $e->getMessage());
        }
    }

    public function obtenerTodasLasMesas(): array{
        try {
            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM mesa");
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

            $mesas = [];

            foreach ($resultados as $fila) {
                $mesas[] = new Mesa($fila);
            }

            return $mesas;
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener las mesas: " . $e->getMessage());
        }
    }

    public function obtenerMesaPorCodigo($parametros): ?Mesa {
        try {
            $codigo = $parametros['codigo'];

            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM mesa WHERE codigo = :codigo");
            $consulta->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                return new Producto($resultado);
            } else {
                return null;
            }
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener la mesa: " . $e->getMessage());
        }
    }

    public function modificarMesa($parametros) {
        try {
            $mesaExistente = $this->obtenerMesaPorCodigo($parametros);

            if ($mesaExistente) {
                $this->actualizarEstadoMesa($parametros);
                $mensaje = "Mesa actualizada exitosamente, paso de estado " . $mesaExistente['estadoMesa'] . " a " . $parametros['estadoMesa'];
            } else {
                $mensaje = "La mesa no existe";
            }

            return $mensaje;
        } catch (Exception $e) {
            throw new RuntimeException("Error al modificar la mesa: " . $e->getMessage());
        }
    }

    public function bajaMesa($parametros) {
        try {
            $mesaExistente = $this->obtenerMesaPorCodigo($parametros);

            if ($mesaExistente) {
                $parametros['estadoMesa'] = EstadoMesaEnum::Baja->value;
                $this->actualizarEstadoMesa($parametros);
                $mensaje = "Mesa eliminada exitosamente";
            } else {
                $mensaje = "La mesa no existe";
            }

            return $mensaje;
        } catch (Exception $e) {
            throw new RuntimeException("Error al eliminar la mesa: " . $e->getMessage());
        }
    }

    public function registrarNuevaMesa($mesa) {
        try {
            $codigo = $mesa->getCodigo();
            $estadoMesa = $mesa->getEstadoMesa();

            $consultaInsert = $this->accesoDatos->prepararConsulta("
                INSERT INTO mesa (codigo, estadoMesa) 
                VALUES (:codigo, :estadoMesa)
            ");
            $consultaInsert->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $consultaInsert->bindParam(':estadoMesa', $estadoMesa, PDO::PARAM_INT);
            $consultaInsert->execute();
        } catch (Exception $e) {
            throw new RuntimeException("Error al registar la mesa: " . $e->getMessage());
        }
    }

    public function actualizarEstadoMesa($parametros) {
        try {
            $estadoMesa = $parametros['estadoMesa'];
            $codigo = $parametros['codigo'];

            $actualizacion = $this->accesoDatos->prepararConsulta("
                UPDATE mesa 
                SET estadoMesa = :estadoMesa
                WHERE codigo = :codigo
            ");
            $actualizacion->bindParam(':estadoMesa', $estadoMesa, PDO::PARAM_INT);
            $actualizacion->bindParam(':codigo', $codigo, PDO::PARAM_INT);
            $actualizacion->execute();
        } catch (Exception $e) {
            throw new RuntimeException("Error al actualizar el estado de la mesa: " . $e->getMessage());
        }
    }
}

?>