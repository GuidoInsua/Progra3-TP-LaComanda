<?php

require_once 'Controllers/AController.php';
require_once 'Services/OrdenService.php';
require_once 'Enums/EstadoPedidoEnum.php';
require_once 'Models/Orden.php ';

class OrdenController extends AController{
    private $miOrdenService;

    public function __construct()
    {
        $this->miOrdenService = new OrdenService();
    }

    public function mostrarOrdenes($request, $response, $args){

        $data = $request->getParsedBody();

        $ordenes = $this->miOrdenService->obtenerOrdenesPorEstado($data);

    }
}


?>