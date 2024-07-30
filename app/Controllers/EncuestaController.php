<?php

require_once 'Models/Encuesta.php';
require_once 'Interfaces/IController.php';
require_once 'Controllers/AController.php';
require_once 'Services/EncuestaService.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class EncuestaController extends AController implements IController {
    private $miEncuestaService;

    public function __construct()
    {
        $this->miEncuestaService = new EncuestaService();
    }

    public function add($request, $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $mensajeRespuesta = $this->miEncuestaService->altaEncuesta($data);

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
            $encuestas = $this->miEncuestaService->obtenerTodasLasEncuestas();

            if ($encuestas != null && count($encuestas) > 0) {  
                $contenido = json_encode(array("Encuestas"=>$encuestas));
            }
            else {
                $contenido = json_encode(array("mensaje"=>"No se encontraron encuestas"));
            }

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al consultar las encuestas " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }

    public function get($request, $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $encuesta = $this->miEncuestaService->obtenerEncuestaPorId($data);

            if ($encuesta != null) {
                $contenido = json_encode(array("Encuesta"=>$encuesta));
            }
            else {
                $contenido = json_encode(array("mensaje"=>"No se encontró la encuesta"));
            }

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al consultar la encuesta " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }

    public function update($request, $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $mensajeRespuesta = $this->miEncuestaService->modificarEncuesta($data);

            $contenido = json_encode(array("mensaje"=>$mensajeRespuesta));

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al modificar la encuesta " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }

    public function delete($request, $response, $args)
    {
        try {
            $data = $request->getParsedBody();

            $mensajeRespuesta = $this->miEncuestaService->bajaEncuesta($data);

            $contenido = json_encode(array("mensaje"=>$mensajeRespuesta));

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al eliminar la encuesta " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }

    public function obtenerMejoresComentarios($request, $response, $args)
    {
        try {
            $encuestas = $this->miEncuestaService->obtenerMejoresComentarios();

            if ($encuestas != null && count($encuestas) > 0) {  
                $contenido = json_encode(array("Encuestas"=>$encuestas));
            }
            else {
                $contenido = json_encode(array("mensaje"=>"No se encontraron encuestas"));
            }

            return $this->setResponse($response, $contenido);
        } catch (Exception $e) {
            $contenido = json_encode(array("mensaje"=>"Error al consultar las encuestas " . $e->getMessage()));

            return $this->setResponse($response, $contenido);
        }
    }

    public function descargarLogoPdf($request, $response, $args) {
        try {
            // Contenido HTML con una imagen de Internet
            $html = '<h1>La comanda</h1>';
            $html .= '<img src="https://cdn.pixabay.com/photo/2016/12/27/13/10/logo-1933884_640.png" alt="Logo de la empresa" />';
    
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            
            // Configuración de opciones
            $options = $dompdf->getOptions();
            $options->set('isRemoteEnabled', true);
            $dompdf->setOptions($options);
    
            $dompdf->render();
    
            // Configuración de cabeceras para la descarga del PDF
            $filename = 'Logo_' . date('Ymd_His') . '.pdf';
            $response = $response->withHeader('Content-Description', 'File Transfer')
                                 ->withHeader('Content-Type', 'application/pdf')
                                 ->withHeader('Content-Disposition', 'attachment; filename=' . $filename)
                                 ->withHeader('Expires', '0')
                                 ->withHeader('Cache-Control', 'must-revalidate')
                                 ->withHeader('Pragma', 'public');
    
            // Obtener el contenido del PDF y enviarlo al cuerpo de la respuesta
            $output = $dompdf->output();
            $response->getBody()->write($output);
    
            return $response;
        } catch (Exception $e) {
            $contenido = json_encode(['mensaje' => 'Error al descargar el PDF: ' . $e->getMessage()]);
            return $response->withStatus(500)->getBody()->write($contenido);
        }
    }
    
    
    
}