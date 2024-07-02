<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;

require_once 'Enums/EstadoPedidoEnum.php';
require_once 'Enums/SectorEnum.php';

class MValidarOrden {

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

        if (count($data) != $expectedParams) {
            $errors['extraFields'] = 'Numero incorrecto de campos. Se esperan ' . $expectedParams . ' campos.';
        }

        foreach ($this->paramsToValidate as $param) {
            switch ($param) {
                case 'estadoOrden':
                    if (empty($data['estadoOrden']) || !is_numeric($data['estadoOrden']) || !EstadoPedidoEnum::fromId((int)$data['estadoOrden'])) {
                        $errors['estadoOrden'] = 'estadoOrden es requerido y debe ser un numero ' . EstadoPedidoEnum::imprimirOpciones();
                    }
                    break;
                case 'idSector':
                    if (empty($data['idSector']) || !is_numeric($data['idSector']) || !SectorEnum::fromId((int)$data['idSector'])) {
                        $errors['idSector'] = 'idSector es requerido y debe ser un numero ' . SectorEnum::imprimirOpciones();
                    }
                    break;
                case 'tiepoEstimado':
                    if (empty($data['tiepoEstimado']) || !is_numeric($data['tiepoEstimado'])) {
                        $errors['tiepoEstimado'] = 'tiepoEstimado es requerido y debe ser un numero';
                    }
                    break;
                default:
                    $errors[$param] = 'Parametro desconocido: ' . $param;
            }
        }

        return $errors;
    }
}

?>