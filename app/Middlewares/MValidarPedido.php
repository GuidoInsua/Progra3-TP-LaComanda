<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;

require_once 'Enums/EstadoPedidoEnum.php';

class MValidarPedido {

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
                case 'codigo':
                    if (empty($data['codigo']) || !is_numeric($data['codigo'])) {
                        $errors['codigo'] = 'codigo es requerido y debe ser un numero';
                    }
                    break;
                case 'nombreCliente':
                    if (empty($data['nombreCliente']) || !is_string($data['nombreCliente'])) {
                        $errors['nombreCliente'] = 'nombreCliente es requerido y debe ser un string';
                    }
                    break;
                case 'idMesa':
                    if (empty($data['idMesa']) || !is_numeric($data['idMesa'])) {
                        $errors['idMesa'] = 'idMesa es requerido y debe ser un numero';
                    }
                    break;
                case 'estadoPedido':
                    if (empty($data['estadoPedido']) || !is_numeric($data['estadoPedido']) || !EstadoPedidoEnum::fromId((int)$data['estadoPedido'])) {
                        $errors['estadoPedido'] = 'estadoPedido es requerido y debe ser un numero ' . EstadoPedidoEnum::imprimirOpciones();
                    }
                    break;
                case 'tiempoEstimado':
                    if (empty($data['tiempoEstimado']) || !is_numeric($data['tiempoEstimado'])) {
                        $errors['tiempoEstimado'] = 'tiempoEstimado es requerido y debe ser un numero';
                    }
                    break;
                case 'fechaBaja':
                    if (empty($data['fechaBaja']) || !is_string($data['fechaBaja'])) {
                        $errors['fechaBaja'] = 'fechaBaja es requerido y debe ser un string';
                    }
                    break;
                case 'precioFinal':
                    if (empty($data['precioFinal']) || !is_numeric($data['precioFinal'])) {
                        $errors['precioFinal'] = 'precioFinal es requerido y debe ser un numero';
                    }
                    break;
                case 'fechaCreacion':
                    if (empty($data['fechaCreacion']) || !is_string($data['fechaCreacion'])) {
                        $errors['fechaCreacion'] = 'fechaCreacion es requerido y debe ser un string';
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