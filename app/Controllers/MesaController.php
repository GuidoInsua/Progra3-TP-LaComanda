<?php

require_once 'Models/Mesa.php';
require_once 'Interfaces/IController.php';
require_once 'Controllers/AController.php';
require_once 'Services/MesaService.php';

class MesaController extends AController implements IController {
    private $miMesaService;

    public function __construct()
    {
        $this->miMesaService = new MesaService();
    }

    public function add($request, $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $mensajeRespuesta = $this->miMesaService->altaMesa($data);

            $contenido = json_encode(array("mensaje"=>$mensajeRespuesta));

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al agregar la mesa " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }

    public function getAll($request, $response, $args)
    {
        try {
            $mesas = $this->miMesaService->obtenerTodasLasMesas();

            if ($mesas != null && count($mesas) > 0) {  
                $contenido = json_encode(array("Mesas"=>$mesas));
            }
            else {
                $contenido = json_encode(array("mensaje"=>"No se encontraron mesas"));
            }

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al consultar las mesas " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }

    public function get($request, $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $mesa = $this->miMesaService->obtenerMesaPorCodigo($data);

            if ($mesa != null) {
                $contenido = json_encode(array("Mesa"=>$mesa));
            }
            else {
                $contenido = json_encode(array("mensaje"=>"No se encontro la mesa"));
            }

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al consultar la mesa " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }

    public function update($request, $response, $args)
    {
        try {

            $data = $request->getParsedBody();

            $mensajeRespuesta = $this->miMesaService->modificarMesa($data);

            $contenido = json_encode(array("mensaje"=>$mensajeRespuesta));

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al modificar la mesa " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }

    public function delete($request, $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $mensajeRespuesta = $this->miMesaService->bajaMesa($data);

            $contenido = json_encode(array("mensaje"=>$mensajeRespuesta));

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al eliminar la mesa " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
       }
    }
}

?>