<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;

require_once 'Enums/EstadoUsuarioEnum.php';
require_once 'Enums/RolEnum.php';

class MValidarUsuario {

    private $paramsToValidate;

    //argumento de número variable, permite que la función o método reciba un número variable de argumentos
    public function __construct(...$paramsToValidate) {
        $this->paramsToValidate = $paramsToValidate;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response {
        if (in_array($request->getMethod(), ['POST', 'PUT'])) {
            $data = $request->getParsedBody();

            if (empty($data)) {
                throw new Exception('Error, No se recibieron datos');
            }

            // Validar los datos según las opciones configuradas
            $errors = $this->validate($data);

            if (!empty($errors)) {
                // Si hay errores, devolver una respuesta con código 400 y los errores
                $response = new SlimResponse();
                $response->getBody()->write(json_encode(['errors' => $errors]));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }
        }

        // Si la solicitud no requiere validación, o si los datos son válidos, pasar al siguiente middleware o ruta
        return $handler->handle($request);
    }

    public function validate($data) {
        $errors = [];
        $expectedParams = count($this->paramsToValidate);

        if (count($data) != $expectedParams) {
            $errors['extraFields'] = 'Numero incorrecto de campos. Se esperan ' . $expectedParams . ' campos.';
        }

        foreach ($this->paramsToValidate as $param) {
            switch ($param) {
                case 'nombre':
                    if (empty($data['nombre']) || !is_string($data['nombre'])) {
                        $errors['nombre'] = 'nombre es requerido y debe ser un string';
                    }
                    break;
                case 'clave':
                    if (empty($data['clave']) || !is_string($data['clave'])) {
                        $errors['clave'] = 'clave es requerido y debe ser un string';
                    }
                    break;
                case 'idRol':
                    if (empty($data['idRol']) || !is_numeric($data['idRol']) || !RolEnum::fromId((int)$data['idRol'])) {
                        $errors['idRol'] = 'idRol es requerido y debe ser un numero' . "<br>" . RolEnum::imprimirOpciones();
                    }
                    break;
                case 'fechaBaja':
                    if (!empty($data['fechaBaja']) && !is_string($data['fechaBaja'])) {
                        $errors['fechaBaja'] = 'fechaBaja debe ser un string';
                    }
                    break;
                case 'fechaAlta':
                    if (!empty($data['fechaAlta']) && !is_string($data['fechaAlta'])) {
                        $errors['fechaAlta'] = 'fechaAlta debe ser un string';
                    }
                    break;
                case 'estadoUsuario':
                    if (empty($data['estadoUsuario']) || !is_numeric($data['estadoUsuario']) || !EstadoUsuarioEnum::fromId((int)$data['estadoUsuario'])) {
                        $errors['estadoUsuario'] = 'estadoUsuario es requerido y debe ser un numero' . "<br>" . EstadoUsuarioEnum::imprimirOpciones();
                    }
                    break;
            }
        }

        return $errors;
    }
}

?>