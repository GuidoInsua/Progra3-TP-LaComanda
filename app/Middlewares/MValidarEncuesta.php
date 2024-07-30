<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;

class MValidarEncuesta {

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

    private function validate($data): array {
        $errors = [];
        $expectedParams = count($this->paramsToValidate);

        if ((count($data))!= $expectedParams) {
            $errors['extraFields'] = 'Numero incorrecto de campos. Se esperan ' . $expectedParams . ' campos.';
        }

        foreach ($this->paramsToValidate as $param) {
            switch ($param) {
                case 'idMesa':
                    if (empty($data['idMesa']) || !is_numeric($data['idMesa'])) {
                        $errors['idMesa'] = 'idMesa es requerido y debe ser un numero';
                    }
                    break;
                case 'codigoPedido':
                    if (empty($data['codigoPedido'])) {
                        $errors['codigoPedido'] = 'codigoPedido es requerido';
                    }
                    break;
                case 'puntuacion':
                    if (empty($data['puntuacion']) || !is_numeric($data['puntuacion']) || $data['puntuacion'] < 1 || $data['puntuacion'] > 10) {
                        $errors['puntuacion'] = 'puntuacion es requerido y debe ser un nuermo entre 1 y 10 ';
                    }
                    break;
                case 'comentario':
                    if (empty($data['comentario']) || !is_string($data['comentario'])) {
                        $errors['comentario'] = 'comentario es requerido y debe ser un string';
                    }
                    break;
                default:
                    $errors[$param] = 'Parametro desconocido: ' . $param;
            }
        }

        return $errors;
    }

    public function validarImagen($archivo) {
        // Verifica que $archivo sea una instancia de UploadedFile
        if ($archivo->getError() !== UPLOAD_ERR_OK) {
            return false;
        }

        $tiposPermitidos = ['image/jpeg', 'image/png'];
        $mimeType = mime_content_type($archivo->getFilePath());

        return in_array($mimeType, $tiposPermitidos);
    }
}

?>