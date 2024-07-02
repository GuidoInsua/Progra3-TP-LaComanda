<?php

require_once 'Models/Mesa.php';
require_once 'Interfaces/IController.php';
require_once 'Controllers/AController.php';
require_once 'Services/MesaService.php';

class MesaController extends AController implements IController {
    private $miMesaController;

    public function __construct()
    {
        $this->miMesaController = new Mesa();
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