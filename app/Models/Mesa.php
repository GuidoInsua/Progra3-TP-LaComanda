<?php

/*
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(5) NOT NULL UNIQUE,
    estado VARCHAR(50) NOT NULL
*/

class Mesa implements JsonSerializable {

    private $_id;
    private $_codigo;
    private $_estado;

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
        $this->_estado = $data['estado'];
    }

    public function __construct2($codigo, $estado) {
        $this->_codigo = $codigo;
        $this->_estado = $estado;
    }

    public function jsonSerialize(): mixed {
        return [
            'id' => $this->_id,
            'codigo' => $this->_codigo,
            'estado' => $this->_estado
        ];
    }

    public function imprimirMesa() {
        echo 
        " - ID: " . $this->_id . "<br>" .
        " - Codigo: " . $this->_codigo . "<br>" .
        " - Estado: " . $this->_estado . "<br> -------------------- <br>";
    }

    public function getId() {
        return $this->_id;
    }

    public function getCodigo() {
        return $this->_codigo;
    }

    public function getEstado() {
        return $this->_estado;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function setCodigo($codigo) {
        $this->_codigo = $codigo;
    }

    public function setEstado($estado) {
        $this->_estado = $estado;
    }

}

?>