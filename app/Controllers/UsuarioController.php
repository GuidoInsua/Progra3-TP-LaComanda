<?php

require_once 'Models/Usuario.php';
require_once 'Interfaces/IController.php';
require_once 'Controllers/AController.php';
require_once 'Services/UsuarioService.php';

class UsuarioController extends AController implements IController {
    private $miUsuarioController;

    public function __construct()
    {
        $this->miUsuarioController = new Usuario();
    }

    public function add($request, $response, $args)
    {
    }

    public function getAll($request, $response, $args)
    {
    }

    public function get($request, $response, $args)
    {
    }

    public function update($request, $response, $args)
    {
    }

    public function delete($request, $response, $args)
    {
    }
}

?>