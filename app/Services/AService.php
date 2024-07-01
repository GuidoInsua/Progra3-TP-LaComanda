<?php

use Psr\Http\Message\UploadedFileInterface;

require_once 'DataBase/AccesoDatos.php';

abstract class AService{

    protected AccesoDatos $accesoDatos;
    
    public function __construct() {
        $this->accesoDatos = AccesoDatos::obtenerInstancia();
    }

    public static function cargarFoto(UploadedFileInterface $archivo, string $nombre, string $destino): string
    {
        try {
            // Verifica si el archivo se ha subido correctamente
            if ($archivo->getError() === UPLOAD_ERR_OK) {
                
                // Obtiene la extensión del archivo original
                $extension = pathinfo($archivo->getClientFilename(), PATHINFO_EXTENSION);
                
                // Genera un nombre para el archivo
                $nombreArchivo = $nombre . '.' . $extension;
                
                // Construye la ruta completa al archivo de destino
                $rutaDestino = $destino . DIRECTORY_SEPARATOR . $nombreArchivo;

                // Mueve el archivo a la ubicación de destino con el nuevo nombre
                $archivo->moveTo($rutaDestino);

                // Retorna el nombre generado para el archivo
                return $nombreArchivo;

            } else {
                throw new Exception("Archivo inválido o error en la carga.");
            }
        } catch (Exception $e) {
            throw new Exception("Error al subir imagen: " . $e->getMessage());
        }
    }
}

?>