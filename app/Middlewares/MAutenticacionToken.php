<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;

class MAutenticacionToken {
    public function __invoke(Request $request, RequestHandler $handler): Response {
        try {
            $token = $this->obtenerToken($request);
            AutentificadorJWT::VerificarToken($token); 
            $response = $handler->handle($request);
        } catch (Exception $e) {
            return $this->crearErrorResponse($e->getMessage());
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    private function obtenerToken(Request $request): string {
        $header = $request->getHeaderLine('Authorization');

        if (!$header) {
            throw new Exception('No se recibió el token');
        }

        $partes = explode("Bearer", $header);
        if (count($partes) < 2) {
            throw new Exception('Formato de token inválido');
        }

        return trim($partes[1]);
    }

    private function crearErrorResponse(string $mensaje): Response {
        $response = new SlimResponse();
        $response->getBody()->write(json_encode(['errors' => $mensaje]));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
}
?>
