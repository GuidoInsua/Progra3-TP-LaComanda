<?php

require_once 'Models/Pedido.php';
require_once 'Interfaces/IController.php';
require_once 'Controllers/AController.php';
require_once 'Services/PedidoService.php';

class PedidoController extends AController implements IController {
    private $miPedidoController;

    public function __construct()
    {
        $this->miPedidoController = new Pedido();
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