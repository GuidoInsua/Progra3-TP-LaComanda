<?php

require_once 'Models/Producto.php';
require_once 'Interfaces/IController.php';
require_once 'Controllers/AController.php';
require_once 'Services/ProductoService.php';

class ProductoController extends AController implements IController {
    private $miProductoController;

    public function __construct()
    {
        $this->miProductoController = new Producto();
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