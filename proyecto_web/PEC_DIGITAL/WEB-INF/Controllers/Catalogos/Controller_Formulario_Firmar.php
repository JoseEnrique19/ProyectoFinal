<?php
include_once("../../Classes/conexion.php");


$idAlumno = "";
$matricula = "";
$nombre ="";
$aPaterno = "";
$aMaterno = "";
$telefono ="";
$grupo = "";
$periodo ="";
$comentario = "";

$idFormulario = "";


if (isset($_POST['id']) && isset($_POST['erase'])) { /*Para eliminar el registro con el id recibido por get */
    //$obj->setIdAlumno($_POST['id']);
    $idAlumno = $_POST['id'];
    $consulta_borrar ="UPDATE alumnos SET activo = 3 WHERE idAlumno = ".$idAlumno;
    
    if ($queryBorr = $con->query($consulta_borrar)) {
        echo "El alumno se eliminó correctamente";
    } else {
        echo "El alumno no se pudo eliminar, ya que contiene datos asociados.";
    }
} else{
    if (isset($_POST['form'])) {
        $parametros = "";
        parse_str($_POST['form'], $parametros);
    }
    $idFormulario = $parametros['idFormulario'];
    if (isset($parametros['id']) && empty($parametros['id'])) {/* Si el id esta vacio, hay que insertar un NUEVO registro */
        foreach ($parametros['campo'] as $i => $value) 
        {   
            $orden = (int) $i;
            $valueST = (string) $value;
          $sql = "INSERT INTO respuestas(noRegistro, orden, asignacionAF,respuesta) VALUES(".$parametros['noRegistro'].",".$orden.",".$parametros['idAsigAF'].",'".$valueST."')";
               if ($query = $con->query($sql)) {
                    echo "El registro <b>" . $i . "</b> se registró correctamente ". $valueST;            
                } else {
                    echo "Error: El registro " . $i . " no se pudo registrar ". $valueST;
                }
        } 
               
    } else {/* Modificar */  
        foreach ($parametros['campo'] as $i => $value) 
        {
            $orden = (int) $i;
            $valueST = (string) $value;
            $sqlac = "UPDATE respuestas SET noRegistro =".$parametros['noRegistro'].", asignacionAF = ".$parametros['idAsigAF'].", respuesta = '".$valueST."' WHERE noRegistro =".$parametros['noRegistro']." AND orden = ".$orden;
            
            if ($query = $con->query($sqlac)) {
                echo "El campo <b>" . $i . "</b> se modificó correctamente ". $valueST;
            } else {
                echo "Error: El campo " . $i . " no se pudo registrar ". $valueST;
            }
        }
    }
      
}

?>