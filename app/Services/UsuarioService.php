<?php

require_once 'Models/Usuario.php';
require_once 'Services/AService.php';
require_once 'Enums/EstadoUsuarioEnum.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

class UsuarioService extends AService {

    public function altaUsuario($parametros) {
        try {
            $usuario = new Usuario($parametros);
            $usuarioExistente = $this->verificarUsuarioExistente($usuario->getNombre());
            if ($usuarioExistente) {
                $mensaje = "El usuario ya existe";
            } else {
                $this->registrarNuevoUsuario($usuario);
                $mensaje = "Usuario dado de alta exitosamente";
            }
            return $mensaje;
        } catch (Exception $e) {
            throw new RuntimeException("Error al dar de alta el usuario: " . $e->getMessage());
        }
    }

    public function obtenerTodosLosUsuarios(): array {
        try {
            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM usuario");
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

            $usuarios = [];

            foreach ($resultados as $fila) {
                $usuarios[] = new Usuario($fila);
            }
            return $usuarios;
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener los usuarios: " . $e->getMessage());
        }
    }

    public function obtenerUnUsuario($parametros): ?Usuario {
        try {
            $usuarioExistente = $this->verificarUsuarioExistente($parametros['nombre']);

            return $usuarioExistente;
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener el usuario: " . $e->getMessage());
        }
    }

    public function modificarUsuario($parametros) {
        try {
            $usuarioExistente = $this->verificarUsuarioExistente($parametros['nombre']);

            if ($usuarioExistente) {
                $this->actualizarEstado($parametros);
                $this->actualizarRol($parametros);
                $mensaje = "Usuario actualizado exitosamente";
            } else {
                $mensaje = "El usuario no existe";
            }

            return $mensaje;
        } catch (Exception $e) {
            throw new RuntimeException("Error al modificar el usuario: " . $e->getMessage());
        }
    }

    public function bajaUsuario($parametros) {
        try {
            $usuarioExistente = $this->verificarUsuarioExistente($parametros['nombre']);

            if ($usuarioExistente) {
                $parametros['estadoUsuario'] = EstadoUsuarioEnum::Inactivo->value;
                $this->actualizarEstado($parametros);
                $mensaje = "Usuario dado de baja exitosamente";
            } else {
                $mensaje = "El usuario no existe";
            }

            return $mensaje;
        } catch (Exception $e) {
            throw new RuntimeException("Error al dar de baja el usuario: " . $e->getMessage());
        }
    }

    private function verificarUsuarioExistente($nombre) {
        try {
            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM usuario WHERE nombre = :nombre");
            $consulta->bindParam(':nombre', $nombre);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                return new Usuario($resultado);
            } else {
                return null;
            }
        } catch (Exception $e) {
            throw new RuntimeException("Error al verificar la existencia del usuario: " . $e->getMessage());
        }
    }

    private function registrarNuevoUsuario($usuario) {
        try {
            $fecha = date('Y-m-d');

            $consulta = $this->accesoDatos->prepararConsulta("
            INSERT INTO usuario (nombre, clave, idRol, fechaAlta, estadoUsuario) 
            VALUES (:nombre, :clave, :idRol, :fechaAlta, :estadoUsuario)
            ");
            $consulta->bindParam(':nombre', $usuario->getNombre());
            $consulta->bindParam(':clave', $usuario->getClave());
            $consulta->bindParam(':idRol', $usuario->getIdRol());
            $consulta->bindParam(':fechaAlta', $fecha);
            $consulta->bindParam(':estadoUsuario', $usuario->getEstadoUsuario());
            $consulta->execute();
        } catch (Exception $e) {
            throw new RuntimeException("Error al registrar el nuevo usuario: " . $e->getMessage());
        }
    }

    private function actualizarEstado($parametros) {
        try {
            $consulta = $this->accesoDatos->prepararConsulta("UPDATE usuario SET estadoUsuario = :estadoUsuario WHERE nombre = :nombre");
            $consulta->bindParam(':estadoUsuario', $parametros['estadoUsuario']);
            $consulta->bindParam(':nombre', $parametros['nombre']);
            $consulta->execute();
        } catch (Exception $e) {
            throw new RuntimeException("Error al actualizar el usuario: " . $e->getMessage());
        }
    }

    private function actualizarRol($parametros) {
        try {
            $consulta = $this->accesoDatos->prepararConsulta("UPDATE usuario SET idRol = :idRol WHERE nombre = :nombre");
            $consulta->bindParam(':idRol', $parametros['idRol']);
            $consulta->bindParam(':nombre', $parametros['nombre']);
            $consulta->execute();
        } catch (Exception $e) {
            throw new RuntimeException("Error al actualizar el rol del usuario: " . $e->getMessage());
        }
    }


}

?>