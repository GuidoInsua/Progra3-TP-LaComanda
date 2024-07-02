<?php

require_once 'Enums/EstadoMesaEnum.php';

class Mesa implements JsonSerializable {

    private $_id;
    private $_codigo;
    private $_estadoMesa; // 1: Esperando Pedido, 2: Comiendo, 3: Pagando, 4: Cerrada, 5: Baja

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
        $this->_codigo = $data['codigo'];
        $this->_estadoMesa = empty($data['estadoMesa']) ? EstadoMesaEnum::Cerrada->value : $data['estadoMesa'];
    }

    public function jsonSerialize(): mixed {
        return [
            'id' => $this->_id,
            'codigo' => $this->_codigo,
            'estadoMesa' => $this->_estadoMesa
        ];
    }

    public function imprimirMesa() {
        echo 
        " - ID: " . $this->_id . "<br>" .
        " - Codigo: " . $this->_codigo . "<br>" .
        " - Estado: " . EstadoMesaEnum::fromId($this->_estadoMesa)?->getNombre() . "<br> -------------------- <br>";
    }

    public function getId() {
        return $this->_id;
    }

    public function getCodigo() {
        return $this->_codigo;
    }

    public function getEstadoMesa() {
        return $this->_estadoMesa;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function setCodigo($codigo) {
        $this->_codigo = $codigo;
    }

    public function setEstadoMesa($estado) {
        $this->_estadoMesa = $estado;
    }

}

?>