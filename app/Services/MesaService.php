<?php

require_once 'Models/Mesa.php';
require_once 'Services/AService.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

class MesaService extends AService {

    public function altaMesa($parametros) {
        try {
            $mesa = new Mesa($parametros);
            $mesaExistente = $this->verificarMesaExistente($mesa->getCodigo());

            if ($mesaExistente) {
                $mensaje = "La mesa ya existe";
            } else {
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

    public function obtenerUnaMesa($parametros): ?Mesa {
        try {
            $mesaExistente = $this->verificarMesaExistente($parametros['codigo']);

            return $mesaExistente;
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener la mesa: " . $e->getMessage());
        }
    }

    public function modificarMesa($parametros) {
        try {
            $mesaExistente = $this->verificarMesaExistente($parametros['codigo']);

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
            $mesaExistente = $this->verificarMesaExistente($parametros['codigo']);

            if ($mesaExistente) {
                $parametros['estadoMesa'] = 5;
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

    private function verificarMesaExistente($codigo) {
        $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM mesa WHERE codigo = :codigo");
        $consulta->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    private function registrarNuevaMesa($mesa) {
        $consultaInsert = $this->accesoDatos->prepararConsulta("
            INSERT INTO mesa (codigo) 
            VALUES (:codigo)
        ");
        $consultaInsert->bindValue(':codigo', $mesa->getCodigo(), PDO::PARAM_STR);
        $consultaInsert->execute();
    }

    private function actualizarEstadoMesa($parametros) {
        $actualizacion = $this->accesoDatos->prepararConsulta("
            UPDATE mesa 
            SET estadoMesa = :estadoMesa
            WHERE codigo = :codigo
        ");
        $actualizacion->bindParam(':estadoMesa', $parametros['estadoMesa'], PDO::PARAM_STR);
        $actualizacion->bindParam(':codigo', $parametros['codigo'], PDO::PARAM_INT);
        $actualizacion->execute();
    }
}

?>