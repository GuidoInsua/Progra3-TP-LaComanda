<?php

interface IController
{
    public function add($request, $response, $args);
    public function getAll($request, $response, $args);
    public function get($request, $response, $args);
    public function update($request, $response, $args);
    public function delete($request, $response, $args);
}

?>