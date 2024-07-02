<?php

require_once 'Models/Usuario.php';
require_once 'Interfaces/IController.php';
require_once 'Controllers/AController.php';
require_once 'Services/UsuarioService.php';

class UsuarioController extends AController implements IController {
    private $miUsuarioService;

    public function __construct()
    {
        $this->miUsuarioService = new UsuarioService();
    }

    public function add($request, $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $mensajeRespuesta = $this->miUsuarioService->altaUsuario($data);

            $contenido = json_encode(array("mensaje"=>$mensajeRespuesta));

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al agregar el usuario " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }

    public function getAll($request, $response, $args)
    {
        try {
            $usuarios = $this->miUsuarioService->obtenerTodosLosUsuarios();

            if ($usuarios != null && count($usuarios) > 0) {
                $contenido = json_encode(array("Usuarios"=>$usuarios));
            }
            else {
                $contenido = json_encode(array("mensaje"=>"No se encontraron usuarios"));
            }

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al consultar los usuarios " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }

    public function get($request, $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $usuario = $this->miUsuarioService->obtenerUsuarioPorNombre($data);

            if ($usuario != null) {
                $contenido = json_encode(array("Usuario"=>$usuario));
            }
            else {
                $contenido = json_encode(array("mensaje"=>"No se encontro el usuario"));
            }

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al consultar el usuario " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }

    public function update($request, $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $mensajeRespuesta = $this->miUsuarioService->modificarUsuario($data);

            $contenido = json_encode(array("mensaje"=>$mensajeRespuesta));

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al modificar el usuario " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }

    public function delete($request, $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $mensajeRespuesta = $this->miUsuarioService->bajaUsuario($data);

            $contenido = json_encode(array("mensaje"=>$mensajeRespuesta));

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al dar de baja el usuario " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }
}

?>