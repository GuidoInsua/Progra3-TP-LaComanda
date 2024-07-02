<?php

abstract class AController {

    protected function setResponse($response, $content) {
        $response->getBody()->write($content);
        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>