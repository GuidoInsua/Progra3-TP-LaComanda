<?php

/*
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(5) NOT NULL UNIQUE,
    nombreCliente VARCHAR(100) NOT NULL,
    idMesa INT,
    estado VARCHAR(50) NOT NULL,
    tiempoEstimado TIME,
    fechaBaja DATE,
    precioFinal DECIMAL(10, 2),
    fechaCreacion DATETIME DEFAULT CURRENT_TIMESTAMP,
*/

class Pedido implements JsonSerializable {

    private $_id;
    private $_codigo;
    private $_nombreCliente;
    private $_idMesa;
    private $_estado;
    private $_tiempoEstimado;
    private $_fechaBaja;
    private $_precioFinal;
    private $_fechaCreacion;

    function __construct(){
        $params = func_get_args();
        
        $num_params = func_num_args();
        
        $funcion_constructor ='__construct'.$num_params;
        
        if (method_exists($this,$funcion_constructor)) {
            call_user_func_array(array($this,$funcion_constructor),$params);
        }
    }

    public function __construct1(array $data) {
        $this->_id = $data['id'];
        $this->_codigo = $data['codigo'];
        $this->_nombreCliente = $data['nombreCliente'];
        $this->_idMesa = $data['idMesa'];
        $this->_estado = $data['estado'];
        $this->_tiempoEstimado = $data['tiempoEstimado'];
        $this->_fechaBaja = $data['fechaBaja'];
        $this->_precioFinal = $data['precioFinal'];
        $this->_fechaCreacion = $data['fechaCreacion'];
    }

    public function __construct8($codigo, $nombreCliente, $idMesa, $estado, $tiempoEstimado, $fechaBaja, $precioFinal, $fechaCreacion) {
        $this->_codigo = $codigo;
        $this->_nombreCliente = $nombreCliente;
        $this->_idMesa = $idMesa;
        $this->_estado = $estado;
        $this->_tiempoEstimado = $tiempoEstimado;
        $this->_fechaBaja = $fechaBaja;
        $this->_precioFinal = $precioFinal;
        $this->_fechaCreacion = $fechaCreacion;
    }

    public function jsonSerialize(): mixed {
        return [
            'id' => $this->_id,
            'codigo' => $this->_codigo,
            'nombreCliente' => $this->_nombreCliente,
            'idMesa' => $this->_idMesa,
            'estado' => $this->_estado,
            'tiempoEstimado' => $this->_tiempoEstimado,
            'fechaBaja' => $this->_fechaBaja,
            'precioFinal' => $this->_precioFinal,
            'fechaCreacion' => $this->_fechaCreacion
        ];
    }

    public function imprimirPedido() {
        echo 
        " - ID: " . $this->_id . "<br>" .
        " - Codigo: " . $this->_codigo . "<br>" .
        " - Nombre Cliente: " . $this->_nombreCliente . "<br>" .
        " - ID Mesa: " . $this->_idMesa . "<br>" .
        " - Estado: " . $this->_estado . "<br>" .
        " - Tiempo Estimado: " . $this->_tiempoEstimado . "<br>" .
        " - Fecha Baja: " . $this->_fechaBaja . "<br>" .
        " - Precio Final: " . $this->_precioFinal . "<br>" .
        " - Fecha Creacion: " . $this->_fechaCreacion . "<br> -------------------- <br>";
    }

    public function getId() {
        return $this->_id;
    }

    public function getCodigo() {
        return $this->_codigo;
    }

    public function getNombreCliente() {
        return $this->_nombreCliente;
    }

    public function getIdMesa() {
        return $this->_idMesa;
    }

    public function getEstado() {
        return $this->_estado;
    }

    public function getTiempoEstimado() {
        return $this->_tiempoEstimado;
    }

    public function getFechaBaja() {
        return $this->_fechaBaja;
    }

    public function getPrecioFinal() {
        return $this->_precioFinal;
    }

    public function getFechaCreacion() {
        return $this->_fechaCreacion;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function setCodigo($codigo) {
        $this->_codigo = $codigo;
    }

    public function setNombreCliente($nombreCliente) {
        $this->_nombreCliente = $nombreCliente;
    }

    public function setIdMesa($idMesa) {
        $this->_idMesa = $idMesa;
    }

    public function setEstado($estado) {
        $this->_estado = $estado;
    }

    public function setTiempoEstimado($tiempoEstimado) {
        $this->_tiempoEstimado = $tiempoEstimado;
    }

    public function setFechaBaja($fechaBaja) {
        $this->_fechaBaja = $fechaBaja;
    }

    public function setPrecioFinal($precioFinal) {
        $this->_precioFinal = $precioFinal;
    }

    public function setFechaCreacion($fechaCreacion) {
        $this->_fechaCreacion = $fechaCreacion;
    }

}


?>