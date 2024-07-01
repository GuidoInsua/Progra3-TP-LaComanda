<?php

abstract class AController {
    protected function setearResponse($response, $content) {
        $response->getBody()->write($content);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    protected function setearResponseError($response, $mensajeError, $codigo) {
        $content = json_encode(array("statusCode"=>$codigo, "error"=>$mensajeError));
        return $this->setearResponse($response, $content);
    }
}

?>