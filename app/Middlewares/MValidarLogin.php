<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;

class MValidarLogin {

    public function __invoke(Request $request, RequestHandler $handler): Response {
        if ($request->getMethod() === 'POST') {
            $data = $request->getParsedBody();

            if (empty($data)) {
                throw new Exception('Error, No se recibieron datos');
            }

            // Validar los datos
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

    // validar mail y password
    private function validate($data): array {
        $errors = [];

        if (count($data) != 2){
            $errors['extraFields'] = 'Numero incorrecto de campos. Se esperan: nombre y clave';
        } 
        else {
            if (empty($data['nombre'])) {
                $errors['nombre'] = 'nombre es requerido';
            }

            if (empty($data['clave'])) {
                $errors['clave'] = 'clave es requerido';
            }
        }

        return $errors;
    }

}

?>