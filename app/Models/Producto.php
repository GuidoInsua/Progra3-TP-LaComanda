<?php

require_once 'Enums/SectorEnum.php';

class Producto implements JsonSerializable {

    private $_id;
    private $_precio;
    private $_tipo;
    private $_idSector; // 1: Tragos y Vinos, 2: Choperas, 3: Cocina, 4: Candy Bar
    private $_fechaBaja;

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
        $this->_precio = $data['precio'];
        $this->_tipo = $data['tipo'];
        $this->_idSector = $data['idSector'];
        $this->_fechaBaja = empty($data['fechaBaja']) ? null : $data['fechaBaja'];
    }

    public function jsonSerialize(): mixed {
        return [
            'id' => $this->_id,
            'precio' => $this->_precio,
            'tipo' => $this->_tipo,
            'idSector' => $this->_idSector,
            'fechaBaja' => $this->_fechaBaja
        ];
    }

    public function imprimirProducto() {
        echo 
        " - ID: " . $this->_id . "<br>" .
        " - Precio: " . $this->_precio . "<br>" .
        " - Tipo: " . $this->_tipo . "<br>" .
        " - Sector: " . SectorEnum::fromId($this->_idSector)?->getNombre() . "<br>" .
        " - Fecha Baja: " . $this->_fechaBaja . "<br> -------------------- <br>";
    }

    public function getId() {
        return $this->_id;
    }

    public function getPrecio() {
        return $this->_precio;
    }

    public function getTipo() {
        return $this->_tipo;
    }

    public function getIdSector() {
        return $this->_idSector;
    }

    public function getFechaBaja() {
        return $this->_fechaBaja;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function setPrecio($precio) {
        $this->_precio = $precio;
    }

    public function setTipo($tipo) {
        $this->_tipo = $tipo;
    }

    public function setIdSector($idSector) {
        $this->_idSector = $idSector;
    }

    public function setFechaBaja($fechaBaja) {
        $this->_fechaBaja = $fechaBaja;
    }

}

?>