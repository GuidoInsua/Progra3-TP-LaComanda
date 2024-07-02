<?php

require_once 'Models/Usuario.php';
require_once 'Controllers/AController.php';
require_once 'Services/LoginService.php';

class LoginController extends AController{

    private $miLoginService;

    public function __construct() {
        $this->miLoginService = new LoginService();
    }

    public function loginUsuario($request, $response, $args) {
        try {
            $data = $request->getParsedBody();

            $mensajeRespuesta = $this->miLoginService->loginUsuario($data);

            $contenido = json_encode(array("mensaje"=>$mensajeRespuesta));

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al loguear usuario " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }
}
?>
