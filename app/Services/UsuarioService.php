<?php

require_once 'Models/Usuario.php';
require_once 'Services/AService.php';
require_once 'Enums/EstadoUsuarioEnum.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

class UsuarioService extends AService {

    public function altaUsuario($parametros) {
        try {
            $usuarioExistente = $this->obtenerUsuarioPorNombre($parametros);
            if ($usuarioExistente) {
                $mensaje = "El usuario ya existe";
            } else {
                $usuario = new Usuario($parametros);
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

    public function obtenerUsuarioPorNombre($parametros): ?Usuario {
        try {
            $nombre = $parametros['nombre'];

            $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM usuario WHERE nombre = :nombre");
            $consulta->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                return new Usuario($resultado);
            } else {
                return null;
            }
        } catch (Exception $e) {
            throw new RuntimeException("Error al obtener usuario: " . $e->getMessage());
        }
    }

    public function modificarUsuario($parametros) {
        try {
            $usuarioExistente = $this->obtenerUsuarioPorNombre($parametros);

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
            $usuarioExistente = $this->obtenerUsuarioPorNombre($parametros);

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

    private function registrarNuevoUsuario($usuario) {
        try {
            $fecha = date('Y-m-d');
            $nombre = $usuario->getNombre();
            $clave = $usuario->getClave();
            $idRol = $usuario->getIdRol();
            $estadoUsuario = $usuario->getEstadoUsuario();
    
            $consulta = $this->accesoDatos->prepararConsulta("
            INSERT INTO usuario (nombre, clave, idRol, fechaAlta, estadoUsuario) 
            VALUES (:nombre, :clave, :idRol, :fechaAlta, :estadoUsuario)
            ");
    
            // Usar las variables en bindParam
            $consulta->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $consulta->bindParam(':clave', $clave, PDO::PARAM_STR);
            $consulta->bindParam(':idRol', $idRol, PDO::PARAM_INT);
            $consulta->bindParam(':fechaAlta', $fecha, PDO::PARAM_STR);
            $consulta->bindParam(':estadoUsuario', $estadoUsuario, PDO::PARAM_INT);
            $consulta->execute();
        } catch (Exception $e) {
            throw new RuntimeException("Error al registrar el nuevo usuario: " . $e->getMessage());
        }
    }

    private function actualizarEstado($parametros) {
        try {
            $estadoUsuario = $parametros['estadoUsuario'];
            $nombre = $parametros['nombre'];

            $consulta = $this->accesoDatos->prepararConsulta("UPDATE usuario SET estadoUsuario = :estadoUsuario WHERE nombre = :nombre");
            $consulta->bindParam(':estadoUsuario', $estadoUsuario, PDO::PARAM_INT);
            $consulta->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $consulta->execute();
        } catch (Exception $e) {
            throw new RuntimeException("Error al actualizar el usuario: " . $e->getMessage());
        }
    }

    private function actualizarRol($parametros) {
        try {
            $idRol = $parametros['idRol'];
            $nombre = $parametros['nombre'];

            $consulta = $this->accesoDatos->prepararConsulta("UPDATE usuario SET idRol = :idRol WHERE nombre = :nombre");
            $consulta->bindParam(':idRol', $idRol, PDO::PARAM_INT);
            $consulta->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $consulta->execute();
        } catch (Exception $e) {
            throw new RuntimeException("Error al actualizar el rol del usuario: " . $e->getMessage());
        }
    }


}

?>