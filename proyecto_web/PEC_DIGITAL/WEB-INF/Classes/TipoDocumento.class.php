<?php

include_once("Catalogo2.class.php");

class TipoDocumento {
    
    private $IdTipoDocumento;
    private $Nombre;
    private $IdDocumentoSuperior;
    private $Activo;
    private $UsuarioCreacion;
    private $FechaCreacion;
    private $UsuarioUltimaModificacion;
    private $FechaUltimaModificacion;
    private $Pantalla;
    
    private $nombreId = "IdTipoDocumento";
    private $tabla = "c_tipo_documento";
    
    public function getRegistroById($id) {
        $consulta = ("SELECT * FROM $this->tabla WHERE $this->nombreId ='" . $id . "'");
        $catalogo = new Catalogo2();
        if (isset($this->empresa)) {
            $catalogo->setEmpresa($this->empresa);
        }
        $query = $catalogo->obtenerLista($consulta);
        if ($rs = mysql_fetch_array($query)) {
            $this->IdTipoDocumento = $rs['IdTipoDocumento'];
            $this->Nombre = $rs['Nombre'];
            $this->IdDocumentoSuperior = $rs['IdDocumentoSuperior'];
            $this->Activo = $rs['Activo'];
            $this->UsuarioCreacion = $rs['UsuarioCreacion'];
            $this->FechaCreacion = $rs['FechaCreacion'];
            $this->UsuarioUltimaModificacion = $rs['UsuarioUltimaModificacion'];
            $this->FechaUltimaModificacion = $rs['FechaUltimaModificacion'];
            $this->Pantalla = $rs['Pantalla'];
        }
        return $query;
    }
    
    public function newRegistro() {
        
        if(!isset($this->IdDocumentoSuperior) || empty($this->IdDocumentoSuperior)){
            $this->IdDocumentoSuperior = "NULL";
        }
        
        $consulta = ("INSERT INTO $this->tabla(IdTipoDocumento, Nombre, IdDocumentoSuperior, Activo,
            UsuarioCreacion,FechaCreacion,UsuarioUltimaModificacion,FechaUltimaModificacion,Pantalla)
            VALUES(0,'$this->Nombre',$this->IdDocumentoSuperior,$this->Activo,
                '$this->UsuarioCreacion',NOW(),'$this->UsuarioUltimaModificacion',NOW(),'$this->Pantalla')");
        //echo $consulta;
        $catalogo = new Catalogo2();
        $this->IdTipoDocumento = $catalogo->insertarRegistro($consulta);
        if ($this->IdTipoDocumento != NULL && $this->IdTipoDocumento != 0) {
            return true;
        }
        return false;
    }
    
    public function editRegistro() {
        
        if(!isset($this->IdDocumentoSuperior) || empty($this->IdDocumentoSuperior)){
            $this->IdDocumentoSuperior = "NULL";
        }
        
        $where = "$this->nombreId =" . $this->IdTipoDocumento;
        $consulta = ("UPDATE $this->tabla SET 
            Nombre = '$this->Nombre',IdDocumentoSuperior = $this->IdDocumentoSuperior,Activo = $this->Activo,
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
        $where = "$this->nombreId = $this->IdTipoDocumento";
        $consulta = ("DELETE FROM `$this->tabla` WHERE $where;");
        $query = $catalogo->ejecutaConsultaActualizacion($consulta, $this->tabla, $where);
        if ($query == 1) {
            return true;
        }
        return false;
    }
    
    function displayChildrenRecursive($xmlObj,$nivelAnterior, $nivel) {
        $contador = 1;
        foreach ($xmlObj->children() as $child) {
            $valor = "";
            $atributos = $child->attributes();
            foreach ($atributos as $key => $value) {    //Solo debe contener 1 atributo el formato
                $valor = $value;
            }
            echo "<div id='nivel_".$nivel."' style='padding-left: 5%;'>
                <div style='margin-top: 6px; float:left;'><input type='text' id='campo_".$nivel."' name='campo_".$nivel."' value='$valor'></div>
                <div>
                    <img src='resources/images/add.png' title='Nuevo campo' width='30px' height='30px' onclick='agregarMismoNivel(\"".$nivelAnterior."\",\"".$nivel."\");return false;' style='cursor: pointer;' />
                    <img src='resources/images/add.png' title='Nuevo campo' width='20px' height='20px' onclick='agregarNuevoNivel(\"".$nivel."\");return false;' style='cursor: pointer;' />
                    <img src='resources/images/Erase.png' title='Eliminar elemento' width='20px' height='20px' onclick='eliminarCampo(\"".$nivelAnterior."\",\"".$nivel."\");return false;' style='cursor: pointer;' />
                </div>";
            echo "<script type= \"text/javascript\">".
                    "aumentarContadorDiv('$nivelAnterior','$nivel');".
                "</script>";
            $this->displayChildrenRecursive($child, $nivel, $nivel."_1");
            echo "</div>";
            $aux = split("_", $nivel);
            $aux[count($aux) - 1] = $aux[count($aux) - 1] + 1;
            $nivel = join("_", $aux);
        }
    }

    function getIdTipoDocumento() {
        return $this->IdTipoDocumento;
    }

    function getNombre() {
        return $this->Nombre;
    }

    function getIdDocumentoSuperior() {
        return $this->IdDocumentoSuperior;
    }

    function getActivo() {
        return $this->Activo;
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

    function setIdTipoDocumento($IdTipoDocumento) {
        $this->IdTipoDocumento = $IdTipoDocumento;
    }

    function setNombre($Nombre) {
        $this->Nombre = $Nombre;
    }

    function setIdDocumentoSuperior($IdDocumentoSuperior) {
        $this->IdDocumentoSuperior = $IdDocumentoSuperior;
    }

    function setActivo($Activo) {
        $this->Activo = $Activo;
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
