<?php

include_once("Catalogo2.class.php");

class Alumnos {
    
    private $IdAlumno;
    private $Matricula;
    private $Nombre;
    private $APaterno;
    private $AMaterno;
    private $Grupo;
    private $Generacion;
    private $Activo;
    private $nombreId = "IdAlumno";
    private $tabla = "alumnos";
    
    public function getRegistroById($id) {
        $consulta = ("SELECT * FROM $this->tabla WHERE $this->nombreId ='" . $id . "'");
        $catalogo = new Catalogo2();
        if (isset($this->empresa)) {
            $catalogo->setEmpresa($this->empresa);
        }
        $query = $catalogo->obtenerLista($consulta);
        if ($rs = mysql_fetch_array($query)) {
            $this->IdAlumno = $rs['IdAlumno'];
            $this->Matricula = $rs['Matricula'];
            $this->Nombre = $rs['Nombre'];
            $this->APaterno = $rs['APaterno'];
            $this->AMaterno = $rs['AMaterno'];
            $this->Grupo = $rs['Grupo'];
            $this->Generacion = $rs['Generacion'];
            $this->Activo = $rs['Activo'];
        }
        return $query;
    }
    
    public function newRegistro() {
        
        if(isset($this->AMaterno) && !empty($this->AMaterno)){
            $this->AMaterno = "'$this->AMaterno'";
        }else{
            $this->AMaterno = "NULL";
        }
        
        $consulta = ("INSERT INTO $this->tabla(IdAlumno,Matricula,Nombre,APaterno,AMaterno,Grupo,Generacion,Activo)
            VALUES(0,$this->Matricula,'$this->Nombre','$this->APaterno',$this->AMaterno,'$this->Grupo','$this->Generacion',$this->Activo)");
        //echo $consulta;
        $catalogo = new Catalogo2();
        $this->IdAlumno = $catalogo->insertarRegistro($consulta);
        if ($this->IdAlumno != NULL && $this->IdAlumno != 0) {
            return true;
        }
        return false;
    }
    
    public function editRegistro() {
        if(isset($this->AMaterno) && !empty($this->AMaterno)){
            $this->AMaterno = "'$this->AMaterno'";
        }else{
            $this->AMaterno = "NULL";
        }
        $where = "$this->nombreId =" . $this->IdAlumno;
        $consulta = ("UPDATE $this->tabla SET Nombre = '$this->Nombre',	APaterno = '$this->APaterno',AMaterno = $this->AMaterno,
            Grupo = '$this->Grupo',Generacion = '$this->Generacion',Activo = $this->Activo
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
        $where = "$this->nombreId = $this->IdAlumno";
        //$consulta = ("DELETE FROM `$this->tabla` WHERE $where;");
        $consulta = ("UPDATE $this->tabla SET Activo = 3 WHERE $where; ");
        $query = $catalogo->ejecutaConsultaActualizacion($consulta, $this->tabla, $where);
        if ($query == 1) {
            return true;
        }
        return false;
    }
    
    function getIdAlumno() {
        return $this->IdAlumno;
    }

    function getMatricula(){
        return $this->Matricula;
    }

    function getNombre() {
        return $this->Nombre;
    }

    function getAPaterno() {
        return $this->APaterno;
    }

    function getAMaterno() {
        return $this->AMaterno;
    }

    function getGrupo() {
        return $this->Grupo;
    }

    function getGeneracion() {
        return $this->Generacion;
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

    function setIdAlumno($IdAlumno) {
        $this->IdAlumno = $IdAlumno;
    }

    function setMatricula($Matricula) {
        $this->Matricula = $Matricula;
    }

    function setNombre($Nombre) {
        $this->Nombre = $Nombre;
    }

    function setAPaterno($APaterno) {
        $this->APaterno = $APaterno;
    }

    function setAMaterno($AMaterno) {
        $this->AMaterno = $AMaterno;
    }

    function setGrupo($Grupo) {
        $this->Grupo = $Grupo;
    }

    function setGeneracion($Generacion) {
        $this->Generacion = $Generacion;
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
    
    function getIdNivelUsuario() {
        return $this->IdNivelUsuario;
    }

    function setIdNivelUsuario($IdNivelUsuario) {
        $this->IdNivelUsuario = $IdNivelUsuario;
    }    
}
