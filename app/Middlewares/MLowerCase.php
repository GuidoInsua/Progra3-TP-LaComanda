<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;

class MLowerCase {

    public function __invoke(Request $request, RequestHandler $handler): Response {
        if (in_array($request->getMethod(), ['POST', 'PUT'])) {
            $data = $request->getParsedBody();

            if (!empty($data)) {
                $lowercasedData = $this->toLowercase($data);
                $request = $request->withParsedBody($lowercasedData);
            }
        }

        return $handler->handle($request);
    }

    private function toLowercase(array $data): array {
        $lowercasedData = [];
        foreach ($data as $key => $value) {
            $lowercasedData[$key] = ($key === 'codigo') ? $value : (is_string($value) ? strtolower($value) : $value);
        }
        return $lowercasedData;
    }
}

?>