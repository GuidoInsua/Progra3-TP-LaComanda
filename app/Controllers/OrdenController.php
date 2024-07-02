<?php

require_once 'Models/Orden.php';
require_once 'Controllers/AController.php';
require_once 'Services/OrdenService.php';
require_once 'Enums/EstadoPedidoEnum.php';

class OrdenController extends AController{
    private $miOrdenService;

    public function __construct()
    {
        $this->miOrdenService = new OrdenService();
    }

    public function mostrarOrdenes($request, $response, $args){
        try {
            $data = $request->getParsedBody();

            $ordenes = $this->miOrdenService->obtenerOrdenesPorEstado($data);

            foreach ($ordenes as $orden) {
                $orden->imprimirOrden();
            }

            $contenido = json_encode(array("mensaje"=>"Exito"));
            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error" . $e->getMessage()));
            return $this->setResponse($response, $contenido);
        }
    }
}


?>