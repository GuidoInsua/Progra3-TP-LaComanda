<?php

require_once 'Models/Producto.php';
require_once 'Interfaces/IController.php';
require_once 'Controllers/AController.php';
require_once 'Services/ProductoService.php';

class ProductoController extends AController implements IController {
    private $miProductoService;

    public function __construct()
    {
        $this->miProductoService = new ProductoService();
    }

    public function add($request, $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $mensajeRespuesta = $this->miProductoService->altaProducto($data);

            $contenido = json_encode(array("mensaje"=>$mensajeRespuesta));

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al agregar el producto " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }

    public function getAll($request, $response, $args)
    {
        try {
            $productos = $this->miProductoService->obtenerTodosLosProductos();

            if ($productos != null && count($productos) > 0) {

                foreach ($productos as $producto) {
                    $producto->imprimirProducto();
                }
    
                $contenido = json_encode(array("mensaje"=>"Consulta exitosa, se encontraron " . count($productos) . " productos"));
            }
            else {
                $contenido = json_encode(array("mensaje"=>"No se encontraron productos"));
            }

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al consultar los productos " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }

    public function get($request, $response, $args)
    {
        try {
            $data = $request->getQueryParams();

            $producto = $this->miProductoService->obtenerUnProducto($data);

            if ($producto != null) {
                $producto->imprimirProducto();
                $contenido = json_encode(array("mensaje"=>"Consulta exitosa"));
            }
            else {
                $contenido = json_encode(array("mensaje"=>"No se encontró el producto"));
            }

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al consultar el producto " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }

    public function update($request, $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $mensajeRespuesta = $this->miProductoService->modificarProducto($data);

            $contenido = json_encode(array("mensaje"=>$mensajeRespuesta));

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al modificar el producto " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }

    public function delete($request, $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $mensajeRespuesta = $this->miProductoService->bajaProducto($data);

            $contenido = json_encode(array("mensaje"=>$mensajeRespuesta));

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al dar de baja el producto " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
    }
}

?>