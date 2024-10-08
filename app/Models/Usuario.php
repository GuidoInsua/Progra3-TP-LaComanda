<?php

require_once 'Enums/EstadoUsuarioEnum.php';
require_once 'Enums/RolEnum.php';

class Usuario implements JsonSerializable {

    private $_id;
    private $_nombre;
    private $_clave;
    private $_idRol;  // 1: Bartender, 2: Cervecero, 3: Cocinero, 4: Mozo, 5: Socio
    private $_fechaBaja;
    private $_fechaAlta;
    private $_estadoUsuario; // 1: Activo, 2: Inactivo

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
        $this->_nombre = $data['nombre'];
        $this->_clave = $data['clave'];
        $this->_idRol = $data['idRol'];
        $this->_fechaBaja = empty($data['fechaBaja']) ? null : $data['fechaBaja'];
        $this->_fechaAlta = empty($data['fechaAlta']) ? null : $data['fechaAlta'];
        $this->_estadoUsuario = empty($data['estadoUsuario']) ? EstadoUsuarioEnum::Activo->value : $data['estadoUsuario'];
    }

    public function jsonSerialize(): mixed {
        return [
            'id' => $this->_id,
            'nombre' => $this->_nombre,
            'clave' => $this->_clave,
            'idRol' => $this->_idRol,
            'fechaBaja' => $this->_fechaBaja,
            'fechaAlta' => $this->_fechaAlta,
            'estadoUsuario' => $this->_estadoUsuario
        ];
    }

    public function imprimirUsuario() {
        echo 
        " - ID: " . $this->_id . "<br>" .
        " - Nombre: " . $this->_nombre . "<br>" .
        " - Clave: " . $this->_clave . "<br>" .
        " - Rol: " . RolEnum::fromId($this->_idRol)?->getNombre() . "<br>" .
        " - Fecha Baja: " . $this->_fechaBaja . "<br>" .
        " - Fecha Alta: " . $this->_fechaAlta . "<br>" .
        " - Estado: " . EstadoUsuarioEnum::fromId($this->_estadoUsuario)?->getNombre() . "<br> -------------------- <br>";
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

    public function getFechaAlta() {
        return $this->_fechaAlta;
    }

    public function setFechaAlta($fechaAlta) {
        $this->_fechaAlta = $fechaAlta;
    }

    public function getEstadoUsuario() {
        return $this->_estadoUsuario;
    }

    public function setEstado($estadoUsuario) {
        $this->_estadoUsuario = $estadoUsuario;
    }

}

?>