<?php

include_once("Catalogo2.class.php");

class Session {

    private $id_usu;
    private $usuario;
    private $password;
    private $id_empresa;
    private $nombre_empresa;
    private $empresa;
    
    private $IntentosFallidosLoggin;
    private $Activo;
    private $arrayEmpresa = array();
    private $arrayActivo = array();
    private $arrayNumeroIntentos = array();

    function getLogin($usuario, $password) {
        $consulta = ("SELECT IdUsuario FROM `c_usuario` WHERE Password='" . $password . "' AND NombreUsuario='" . $usuario . "' AND Activo = 1;");
        //echo $consulta;
        $catalogo = new Catalogo2();      
        $query = $catalogo->obtenerLista($consulta);
        while ($rs = mysql_fetch_array($query)) {
            $this->id_usu = $rs['IdUsuario'];
            $this->usuario = $usuario;
            $this->password = $password;
            return $this->id_usu;
        }
        return "";
    }

    
    public function desencriptarContrasena($password){  
        //Se toman solo los caracteres que forman el real password
        $password_real = substr($password, 0, 8);
        $password_real .= substr($password, 13, 10);
        $password_real .= substr($password, 28, 8);
        $password_real .= substr($password, 41, strlen($password)-1);
        return $password_real;
    }
    
    
    
    public function getId_usu() {
        return $this->id_usu;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getId_empresa() {
        return $this->id_empresa;
    }

    public function getNombre_empresa() {
        return $this->nombre_empresa;
    }

    public function setNombre_empresa($nombre_empresa) {
        $this->nombre_empresa = $nombre_empresa;
    }

    public function getEmpresa() {
        return $this->empresa;
    }

    public function setEmpresa($empresa) {
        $this->empresa = $empresa;
    }

    function getIntentosFallidosLoggin() {
        return $this->IntentosFallidosLoggin;
    }

    function setIntentosFallidosLoggin($IntentosFallidosLoggin) {
        $this->IntentosFallidosLoggin = $IntentosFallidosLoggin;
    }
    
    function getActivo() {
        return $this->Activo;
    }

    function setActivo($Activo) {
        $this->Activo = $Activo;
    }   
    
    function setId_usu($id_usu) {
        $this->id_usu = $id_usu;
    } 
    
    function getArrayEmpresa() {
        return $this->arrayEmpresa;
    }

    function getArrayActivo() {
        return $this->arrayActivo;
    }

    function getArrayNumeroIntentos() {
        return $this->arrayNumeroIntentos;
    }

    function setId_empresa($id_empresa) {
        $this->id_empresa = $id_empresa;
    }
}

