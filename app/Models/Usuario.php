<?php

class Usuario implements JsonSerializable {

    private $_id;
    private $_nombre;
    private $_clave;
    private $_idRol;
    private $_fechaBaja;
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
        $this->_nombre = $data['nombre'];
        $this->_clave = $data['clave'];
        $this->_idRol = $data['idRol'];
        $this->_fechaBaja = $data['fechaBaja'];
        $this->_estado = $data['estado'];
    }

    public function __construct5($nombre, $clave, $idRol, $fechaBaja, $estado) {
        $this->_nombre = $nombre;
        $this->_clave = $clave;
        $this->_idRol = $idRol;
        $this->_fechaBaja = $fechaBaja;
        $this->_estado = $estado;
    }

    public function jsonSerialize(): mixed {
        return [
            'id' => $this->_id,
            'nombre' => $this->_nombre,
            'clave' => $this->_clave,
            'idRol' => $this->_idRol,
            'fechaBaja' => $this->_fechaBaja,
            'estado' => $this->_estado
        ];
    }

    public function imprimirUsuario() {
        echo 
        " - ID: " . $this->_id . "<br>" .
        " - Nombre: " . $this->_nombre . "<br>" .
        " - Clave: " . $this->_clave . "<br>" .
        " - ID Rol: " . $this->_idRol . "<br>" .
        " - Fecha Baja: " . $this->_fechaBaja . "<br>" .
        " - Estado: " . $this->_estado . "<br> -------------------- <br>";
    }

    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function getNombre() {
        return $this->_nombre;
    }

    public function setNombre($nombre) {
        $this->_nombre = $nombre;
    }

    public function getClave() {
        return $this->_clave;
    }

    public function setClave($clave) {
        $this->_clave = $clave;
    }

    public function getIdRol() {
        return $this->_idRol;
    }

    public function setIdRol($idRol) {
        $this->_idRol = $idRol;
    }

    public function getFechaBaja() {
        return $this->_fechaBaja;
    }

    public function setFechaBaja($fechaBaja) {
        $this->_fechaBaja = $fechaBaja;
    }

    public function getEstado() {
        return $this->_estado;
    }

    public function setEstado($estado) {
        $this->_estado = $estado;
    }

}

?>