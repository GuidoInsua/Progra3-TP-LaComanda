<?php

class Encuesta implements JsonSerializable {

    private $_id;
    private $_idMesa;
    private $_codigoPedido;
    private $_puntuacion;
    private $_comentario;

    function __construct(){
        $params = func_get_args();
        
        $num_params = func_num_args();
        
        $funcion_constructor ='__construct'.$num_params;
        
        if (method_exists($this,$funcion_constructor)) {
            call_user_func_array(array($this,$funcion_constructor),$params);
        }
    }

    public function __construct1(array $data) {
        $this->_id = empty($data['id']) ? null : $data['id'];
        $this->_idMesa = $data['idMesa'];
        $this->_codigoPedido = $data['codigoPedido'];
        $this->_puntuacion = $data['puntuacion'];
        $this->_comentario = $data['comentario'];
    }

    public function jsonSerialize(): mixed {
        return [
            'id' => $this->_id,
            'idMesa' => $this->_idMesa,
            'codigoPedido' => $this->_codigoPedido,
            'puntuacion' => $this->_puntuacion,
            'comentario' => $this->_comentario
        ];
    }

    public function imprimirEncuesta() {
        echo 
        " - ID: " . $this->_id . "<br>" .
        " - ID Mesa: " . $this->_idMesa . "<br>" .
        " - Codigo Pedido: " . $this->_codigoPedido . "<br>" .
        " - Puntuacion: " . $this->_puntuacion . "<br>" .
        " - Comentario: " . $this->_comentario . "<br> -------------------- <br>";
    }

    public function getId() {
        return $this->_id;
    }

    public function getIdMesa() {
        return $this->_idMesa;
    }

    public function getCodigoPedido() {
        return $this->_codigoPedido;
    }

    public function getPuntuacion() {
        return $this->_puntuacion;
    }

    public function getComentario() {
        return $this->_comentario;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function setIdMesa($idMesa) {
        $this->_idMesa = $idMesa;
    }

    public function setCodigoPedido($codigoPedido) {
        $this->_codigoPedido = $codigoPedido;
    }

    public function setPuntuacion($puntuacion) {
        $this->_puntuacion = $puntuacion;
    }

    public function setComentario($comentario) {
        $this->_comentario = $comentario;
    }
}