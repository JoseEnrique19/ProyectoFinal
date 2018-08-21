<?php

include_once("Catalogo2.class.php");

class AtributosPlantilla {
    
    private $IdAtributoPlantilla;
    private $IdTipoDocumento;
    private $Descripcion;
    private $IdAtributoSuperior;
    private $UsuarioCreacion;
    private $FechaCreacion;
    private $UsuarioUltimaModificacion;
    private $FechaUltimaModificacion;
    private $Pantalla;

    private $nombreId = "IdAtributoPlantila";
    private $tabla = "atributos_plantilla";
    
    public function getRegistroById($id) {
        $consulta = ("SELECT * FROM $this->tabla WHERE $this->nombreId ='" . $id . "'");
        $catalogo = new Catalogo2();
        if (isset($this->empresa)) {
            $catalogo->setEmpresa($this->empresa);
        }
        $query = $catalogo->obtenerLista($consulta);
        if ($rs = mysql_fetch_array($query)) {
            $this->IdAtributoPlantilla = $rs['IdAtributoPlantilla'];
            $this->IdTipoDocumento = $rs['IdTipoDocumento'];
            $this->Descripcion = $rs['Descripcion'];
            $this->IdAtributoSuperior = $rs['IdAtributoSuperior'];
            $this->UsuarioCreacion = $rs['UsuarioCreacion'];
            $this->FechaCreacion = $rs['FechaCreacion'];
            $this->UsuarioUltimaModificacion = $rs['UsuarioUltimaModificacion'];
            $this->FechaUltimaModificacion = $rs['FechaUltimaModificacion'];
            $this->Pantalla = $rs['Pantalla'];
        }
        return $query;
    }
    
    public function newRegistro() {
        
        if(!isset($this->IdAtributoSuperior) || empty($this->IdAtributoSuperior)){
            $this->IdAtributoSuperior = "NULL";
        }
        
        $consulta = ("INSERT INTO $this->tabla(IdAtributoPlantilla,IdTipoDocumento,Descripcion,IdAtributoSuperior,
            UsuarioCreacion,FechaCreacion,UsuarioUltimaModificacion,FechaUltimaModificacion,Pantalla)
            VALUES(0,$this->IdTipoDocumento,'$this->Descripcion',$this->IdAtributoSuperior,
                '$this->UsuarioCreacion',NOW(),'$this->UsuarioUltimaModificacion',NOW(),'$this->Pantalla')");
        //echo $consulta;
        $catalogo = new Catalogo2();
        $this->IdAtributoPlantilla = $catalogo->insertarRegistro($consulta);
        if ($this->IdAtributoPlantilla != NULL && $this->IdAtributoPlantilla != 0) {
            return true;
        }
        return false;
    }
    
    public function editRegistro() {
        
        if(!isset($this->IdAtributoSuperior) || empty($this->IdAtributoSuperior)){
            $this->IdAtributoSuperior = "NULL";
        }
        
        $where = "$this->nombreId =" . $this->IdAtributoPlantilla;
        $consulta = ("UPDATE $this->tabla SET 
            IdTipoDocumento = $this->IdTipoDocumento,Descripcion = '$this->Descripcion',IdAtributoSuperior = $this->IdAtributoSuperior,
            UsuarioUltimaModificacion = '$this->UsuarioUltimaModificacion',FechaUltimaModificacion = NOW(),Pantalla = '$this->Pantalla' 
            WHERE $where;");
        $catalogo = new Catalogo2();
        $query = $catalogo->ejecutaConsultaActualizacion($consulta, $this->tabla, $where);
        if ($query == 1) {
            return true;
        }
        return false;
    }
    
    function deleteRegistro() {
        $catalogo = new Catalogo2();
        $where = "$this->nombreId = $this->IdAtributoPlantilla";
        $consulta = ("DELETE FROM `$this->tabla` WHERE $where;");
        $query = $catalogo->ejecutaConsultaActualizacion($consulta, $this->tabla, $where);
        if ($query == 1) {
            return true;
        }
        return false;
    }
    
    function deleteByIdTipoDocumento(){
        $catalogo = new Catalogo2();
        $where = "IdTipoDocumento = $this->IdTipoDocumento";
        $consulta = ("DELETE FROM `$this->tabla` WHERE $where;");
        $query = $catalogo->ejecutaConsultaActualizacion($consulta, $this->tabla, $where);
        if ($query == 1) {
            return true;
        }
        return false;
    }
    
    function getHijosPorIdAtributoPlantilla(){
        $catalogo = new Catalogo2();
        $consulta = "SELECT IdAtributoPlantilla,Descripcion, IdAtributoSuperior
            FROM (SELECT * FROM atributos_plantilla
                    ORDER BY IdAtributoSuperior, IdAtributoPlantilla) products_sorted,
                    (SELECT @pv := '".$this->IdAtributoPlantilla."') initialisation
            WHERE find_in_set(IdAtributoSuperior, @pv) > 0
            AND @pv := concat(@pv, ',', IdAtributoPlantilla)";
        $result = $catalogo->obtenerLista($consulta);
        return $result;
    }
    
    function getIdAtributoPlantilla() {
        return $this->IdAtributoPlantilla;
    }

    function getIdTipoDocumento() {
        return $this->IdTipoDocumento;
    }

    function getDescripcion() {
        return $this->Descripcion;
    }

    function getIdAtributoSuperior() {
        return $this->IdAtributoSuperior;
    }

    function getUsuarioCreacion() {
        return $this->UsuarioCreacion;
    }

    function getFechaCreacion() {
        return $this->FechaCreacion;
    }

    function getUsuarioUltimaModificacion() {
        return $this->UsuarioUltimaModificacion;
    }

    function getFechaUltimaModificacion() {
        return $this->FechaUltimaModificacion;
    }

    function getPantalla() {
        return $this->Pantalla;
    }

    function setIdAtributoPlantilla($IdAtributoPlantilla) {
        $this->IdAtributoPlantilla = $IdAtributoPlantilla;
    }

    function setIdTipoDocumento($IdTipoDocumento) {
        $this->IdTipoDocumento = $IdTipoDocumento;
    }

    function setDescripcion($Descripcion) {
        $this->Descripcion = $Descripcion;
    }

    function setIdAtributoSuperior($IdAtributoSuperior) {
        $this->IdAtributoSuperior = $IdAtributoSuperior;
    }

    function setUsuarioCreacion($UsuarioCreacion) {
        $this->UsuarioCreacion = $UsuarioCreacion;
    }

    function setFechaCreacion($FechaCreacion) {
        $this->FechaCreacion = $FechaCreacion;
    }

    function setUsuarioUltimaModificacion($UsuarioUltimaModificacion) {
        $this->UsuarioUltimaModificacion = $UsuarioUltimaModificacion;
    }

    function setFechaUltimaModificacion($FechaUltimaModificacion) {
        $this->FechaUltimaModificacion = $FechaUltimaModificacion;
    }

    function setPantalla($Pantalla) {
        $this->Pantalla = $Pantalla;
    }
    
}
