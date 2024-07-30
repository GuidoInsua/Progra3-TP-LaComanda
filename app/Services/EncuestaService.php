<?php

require_once 'Models/Encuesta.php';
require_once 'Services/AService.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

class EncuestaService extends AService {

    public function altaEncuesta($parametros) {
        try {
            if ($this->existeEncuesta($parametros)) {
                $mensaje = "La encuesta ya existe";
            } else {
                $encuesta = new Encuesta($parametros);
                $this->registrarNuevaEncuesta($encuesta);
                $mensaje = "Encuesta dada de alta exitosamente";
            }

            return $mensaje;
        } catch (Exception $e) {
            throw new RuntimeException("Error al dar de alta la encuesta: " . $e->getMessage());
        }
    }

    public function obtenerTodasLasEncuestas(): array{
        try {
            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM encuesta");
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

            $encuestas = [];

            foreach ($resultados as $fila) {
                $encuestas[] = new Encuesta($fila);
            }

            return $encuestas;
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener las encuestas: " . $e->getMessage());
        }
    }

    public function existeEncuesta($parametros)
    {
        $idMesa = $parametros['idMesa'];
        $codigoPedido = $parametros['codigoPedido'];

        $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM encuesta WHERE idMesa = :idMesa AND codigoPedido = :codigoPedido");
        $consulta->bindParam(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->bindParam(':codigoPedido', $codigoPedido, PDO::PARAM_STR);
        $consulta->execute();

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $encuesta = new Encuesta($resultado);
            return $encuesta;
        } else {
            return false;
        }
    }

    public function registrarNuevaEncuesta($encuesta)
    {
        $idMesa = $encuesta->getIdMesa();
        $codigoPedido = $encuesta->getCodigoPedido();
        $puntuacion = $encuesta->getPuntuacion();
        $comentario = $encuesta->getComentario();

        $consulta = $this->accesoDatos->prepararConsulta("INSERT INTO encuesta (idMesa, codigoPedido, puntuacion, comentario) 
            VALUES (:idMesa, :codigoPedido, :puntuacion, :comentario)");
        $consulta->bindParam(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->bindParam(':codigoPedido', $codigoPedido, PDO::PARAM_STR);
        $consulta->bindParam(':puntuacion', $puntuacion, PDO::PARAM_INT);
        $consulta->bindParam(':comentario', $comentario, PDO::PARAM_STR);
        $consulta->execute();
    }

    public function obtenerEncuestaPorId($parametros): ?Encuesta {
        try {
            $id = $parametros['id'];

            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM encuesta WHERE id = :id");
            $consulta->bindParam(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                return new Encuesta($resultado);
            } else {
                return null;
            }
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener la encuesta: " . $e->getMessage());
        }
    }

    public function modificarEncuesta($parametros) {
        try {
            $id = $parametros['id'];
            $puntuacion = $parametros['puntuacion'];
            $comentario = $parametros['comentario'];

            $actualizacion = $this->accesoDatos->prepararConsulta("UPDATE encuesta SET puntuacion = :puntuacion, comentario = :comentario WHERE id = :id");
            $actualizacion->bindParam(':puntuacion', $puntuacion, PDO::PARAM_INT);
            $actualizacion->bindParam(':comentario', $comentario, PDO::PARAM_STR);
            $actualizacion->bindParam(':id', $id, PDO::PARAM_INT);
            $actualizacion->execute();

            return "Encuesta modificada exitosamente";
        } catch (Exception $e) {
            throw new RuntimeException("Error al modificar la encuesta: " . $e->getMessage());
        }
    }

    public function bajaEncuesta($parametros) {
        try {
            $id = $parametros['id'];

            $baja = $this->accesoDatos->prepararConsulta("DELETE FROM encuesta WHERE id = :id");
            $baja->bindParam(':id', $id, PDO::PARAM_INT);
            $baja->execute();

            return "Encuesta eliminada exitosamente";
        } catch (Exception $e) {
            throw new RuntimeException("Error al eliminar la encuesta: " . $e->getMessage());
        }
    }

    public function obtenerEncuestaPorMesa($parametros): ?Encuesta {
        try {
            $idMesa = $parametros['idMesa'];

            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM encuesta WHERE idMesa = :idMesa");
            $consulta->bindParam(':idMesa', $idMesa, PDO::PARAM_INT);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                return new Encuesta($resultado);
            } else {
                return null;
            }
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener la encuesta de la mesa: " . $e->getMessage());
        }
    }

    
    public function obtenerMejoresComentarios()
    {
        try {
            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM encuesta ORDER BY puntuacion DESC LIMIT 3");
            $consulta->execute();

            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

            $encuestas = [];

            foreach ($resultados as $fila) {
                $encuestas[] = new Encuesta($fila);
            }

            return $encuestas;
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener las encuestas: " . $e->getMessage());
        }
    }
}