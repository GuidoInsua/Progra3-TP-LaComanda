<?php

require_once 'Enums/EstadoPedidoEnum.php';

class Orden implements JsonSerializable {

    private $_id;
    private $_idPedido;
    private $_idProducto;
    private $_idUsuario;
    private $_estadoOrden;
    private $_tiempoEstimado;

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
        $this->_idPedido = $data['idPedido'];
        $this->_idProducto = $data['idProducto'];
        $this->_idUsuario = empty($data['idUsuario']) ? null : $data['idUsuario'];
        $this->_estadoOrden = empty($data['estadoOrden']) ? EstadoPedidoEnum::Pendiente->value : $data['estadoOrden'];
        $this->_tiempoEstimado = empty($data['tiempoEstimado']) ? null : $data['tiempoEstimado'];
    }

    public function jsonSerialize(): mixed {
        return [
            'id' => $this->_id,
            'idPedido' => $this->_idPedido,
            'idProducto' => $this->_idProducto,
            'idUsuario' => $this->_idUsuario,
            'estadoOrden' => $this->_estadoOrden,
            'tiempoEstimado' => $this->_tiempoEstimado
        ];
    }

    public function imprimirOrden() {
        echo
        " - ID: " . $this->_id . "<br>" .
        " - ID Pedido: " . $this->_idPedido . "<br>" .
        " - ID Producto: " . $this->_idProducto . "<br>" .
        " - ID Usuario: " . $this->_idUsuario . "<br>" .
        " - Estado: " . $this->_estadoOrden . "<br>" .
        " - Tiempo Estimado: " . $this->_tiempoEstimado . "<br>";
    }

    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function getIdPedido() {
        return $this->_idPedido;
    }

    public function setIdPedido($idPedido) {
        $this->_idPedido = $idPedido;
    }

    public function getIdProducto() {
        return $this->_idProducto;
    }

    public function setIdProducto($idProducto) {
        $this->_idProducto = $idProducto;
    }

    public function getIdUsuario() {
        return $this->_idUsuario;
    }

    public function setIdUsuario($idUsuario) {
        $this->_idUsuario = $idUsuario;
    }

    public function getEstadoOrden() {
        return $this->_estadoOrden;
    }

    public function setEstadoOrden($estadoOrden) {
        $this->_estadoOrden = $estadoOrden;
    }

    public function getTiempoEstimado() {
        return $this->_tiempoEstimado;
    }

    public function setTiempoEstimado($tiempoEstimado) {
        $this->_tiempoEstimado = $tiempoEstimado;
    
    }
}

?>