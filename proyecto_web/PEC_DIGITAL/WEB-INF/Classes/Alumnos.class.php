<?php

include_once("Catalogo2.class.php");

class Alumnos {
    
    private $IdAlumno;
    private $IdPersona;
    private $Matricula;
    private $Nombre;
    private $APaterno;
    private $AMaterno;
    private $Telefono;
    private $Grupo;
    private $Periodo;
    private $Activo;
    private $Persona;
    private $nombreId = "idAlumno";
    private $personaId = "idPersona";
    private $tabla = "alumnos";
    private $tabla2 = "personas";
    
    public function getRegistroById($id) {
        $consulta = ("SELECT * FROM $this->tabla2 INNER JOIN $this->tabla ON $this->tabla.persona = $this->tabla2.idPersona WHERE $this->nombreId ='" . $id . "'");
        //$consulta = ("SELECT * FROM $this->tabla WHERE $this->nombreId ='" . $id . "'");
        $catalogo = new Catalogo2();
        $query = $catalogo->obtenerLista($consulta);
        if ($rs = mysql_fetch_array($query)) {
            $this->IdAlumno = $rs['idAlumno'];
            $this->IdPersona = $rs['idPersona'];
            $this->Matricula = $rs['matricula'];
            $this->Nombre = $rs['nombre'];
            $this->APaterno = $rs['aPaterno'];
            $this->AMaterno = $rs['aMaterno'];
            $this->Telefono = $rs['telefono'];
            $this->Persona = $rs['persona'];
            $this->Grupo = $rs['grupo'];
            $this->Periodo = $rs['periodo'];
            $this->Activo = $rs['activo'];
        }
        return $query;
    }
    
    public function newRegistro() {
        
        if(isset($this->AMaterno) && !empty($this->AMaterno)){
            $this->AMaterno = "'$this->AMaterno'";
        }else{
            $this->AMaterno = "NULL";
        }
        
        $consulta = ("CALL ALUMNO($this->Matricula,'$this->Nombre','$this->APaterno','$this->AMaterno', '$this->Telefono', '$this->Grupo', '$this->Periodo',$this->Activo)");
        /*$consulta = ("INSERT INTO $this->tabla(IdAlumno,Matricula,Nombre,APaterno,AMaterno,Grupo,Periodo,Activo)
            VALUES(0,$this->Matricula,'$this->Nombre','$this->APaterno',$this->AMaterno,'$this->Grupo','$this->Periodo',$this->Activo)");*/
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
            Grupo = '$this->Grupo',Periodo = '$this->Periodo',Activo = $this->Activo
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
    function getTelefono() {
        return $this->Telefono;
    }

    function getGrupo() {
        return $this->Grupo;
    }

    function getPeriodo() {
        return $this->Periodo;
    }

    function getActivo() {
        return $this->Activo;
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

    function setTelefono($Telefono) {
        $this->Telefono = $Telefono;
    }

    function setGrupo($Grupo) {
        $this->Grupo = $Grupo;
    }

    function setPeriodo($Periodo) {
        $this->Periodo = $Periodo;
    }

    function setActivo($Activo) {
        $this->Activo = $Activo;
    }

}
