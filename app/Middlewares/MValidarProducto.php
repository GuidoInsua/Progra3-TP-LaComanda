<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;

require_once 'Enums/SectorEnum.php';

class MValidarProducto{

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
                case 'precio':
                    if (empty($data['precio']) || !is_numeric($data['precio'])) {
                        $errors['precio'] = 'precio es requerido y debe ser un numero';
                    }
                    break;
                case 'tipo':
                    if (empty($data['tipo']) || !is_string($data['tipo'])) {
                        $errors['tipo'] = 'tipo es requerido y debe ser un string';
                    }
                    break;
                case 'idSector':
                    if (empty($data['idSector']) || !is_numeric($data['idSector']) || !SectorEnum::fromId((int)$data['idSector'])) {
                        $errors['idSector'] = 'idSector es requerido y debe ser un numero ' . SectorEnum::imprimirOpciones();
                    }
                    break;
                case 'fechaBaja':
                    if (empty($data['fechaBaja']) || !is_string($data['fechaBaja'])) {
                        $errors['fechaBaja'] = 'fechaBaja es requerido y debe ser un string';
                    }
                    break;
            }
        }

        return $errors;
    }
}

?>