<?php

require_once 'Services/AService.php';
require_once 'Models/Usuario.php';
require_once 'Utils/AutentificadorJWT.php';
require_once 'Enums/RolEnum.php';

class LoginService extends AService {

    public function loginUsuario($parametros) {
        try {
            $usuario = $this->obtenerUsuario($parametros['nombre'], $parametros['clave']);
            if ($usuario) {
                return $this->crearToken($usuario);
            } else {
                return ['error' => 'nombre o clave incorrectos'];
            }
        } catch (Exception $e) {
            throw new Exception("Error al iniciar sesion: " . $e->getMessage());
        }
    }

    private function obtenerUsuario($nombre, $clave) {
        $consulta = $this->accesoDatos->prepararConsulta("SELECT * FROM usuario WHERE nombre = :nombre AND clave = :clave");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
        $consulta->execute();
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            return new Usuario($resultado);
        }
        return null;
    }

    private function crearToken($usuario) {
        $datos = ['rol' => $usuario->getIdRol()];
        $token = AutentificadorJWT::CrearToken($datos);
        return ['jwt' => $token];
    }
}
?>
