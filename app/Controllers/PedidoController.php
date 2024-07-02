<?php

require_once 'Models/Pedido.php';
require_once 'Interfaces/IController.php';
require_once 'Controllers/AController.php';
require_once 'Services/PedidoService.php';

class PedidoController extends AController implements IController {
    private $miPedidoService;

    public function __construct()
    {
        $this->miPedidoService = new PedidoService();
    }

    public function add($request, $response, $args)
    {
        try {

            $data = $request->getParsedBody();

            $mensajeRespuesta = $this->miPedidoService->altaPedido($data);

            $contenido = json_encode(array("mensaje"=>$mensajeRespuesta));

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al agregar el pedido " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }

    public function getAll($request, $response, $args)
    {
        try {
            $pedidos = $this->miPedidoService->obtenerTodosLosPedidos();

            if ($pedidos != null && count($pedidos) > 0) {
                $contenido = json_encode(array("Pedidos"=>$pedidos));
            }
            else {
                $contenido = json_encode(array("mensaje"=>"No se encontraron pedidos"));
            }

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al consultar los pedidos " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }

    public function get($request, $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $pedido = $this->miPedidoService->obtenerUnPedido($data);

            if ($pedido != null) {
                $contenido = json_encode(array("Pedido"=>$pedido));
            }
            else {
                $contenido = json_encode(array("mensaje"=>"No se encontro el pedido"));
            }

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al consultar el pedido " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }

    public function update($request, $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $mensajeRespuesta = $this->miPedidoService->modificarPedido($data);

            $contenido = json_encode(array("mensaje"=>$mensajeRespuesta));

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al modificar el pedido " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }

    public function delete($request, $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $mensajeRespuesta = $this->miPedidoService->bajaPedido($data);

            $contenido = json_encode(array("mensaje"=>$mensajeRespuesta));

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al eliminar el pedido " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }

    public function obtenerTiempoEstimadoPorMesa($request, $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $mensajeRespuesta = $this->miPedidoService->obtenerTiempoEstimadoPorMesa($data);

            $contenido = json_encode(array("mensaje"=>$mensajeRespuesta));

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al consultar el tiempo estimado " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }
}

?>