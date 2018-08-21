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
    $matricula = $parametros['matricula'];
    $nombre = $parametros['nombre'];
    $aPaterno = $parametros['aPaterno'];
    $aMaterno = $parametros['aMaterno'];
    $telefono = $parametros['telefono'];
    $grupo = $parametros['grupo'];
    $periodo = $parametros['periodo'];
    $comentario = $parametros['comentario'];
    if (isset($parametros['activo']) && $parametros['activo'] == "on") {
            $activo = 1;
    } else {
            $activo = 0;
    }  
    if (isset($parametros['id']) && empty($parametros['id'])) {/* Si el id esta vacio, hay que insertar un NUEVO registro */
        $consulta = "SELECT p.Nombre, al.Matricula FROM personas p INNER JOIN alumnos al ON al.persona = p.idPersona WHERE al.Matricula = ".$parametros["matricula"];
        $result = $con->query($consulta);
        if (mysqli_num_rows($result)==0) {
            $sql = "CALL ALUMNO(".$parametros["matricula"].",'".$parametros["nombre"]."','".$parametros["aPaterno"]."','".$parametros["aMaterno"]."','".$parametros["telefono"]."','".$parametros["grupo"]."','".$parametros["periodo"]."',".$activo.",'".$comentario."')";
            if ($query = $con->query($sql)) {
                echo "El alumno <b>" . $nombre . "</b> se registró correctamente";            
            } else {
                echo "Error: El alumno " . $nombre . " no se pudo registrar";
            }
        }else{
            echo "Error: El alumno " . $nombre . " no se pudo registrar ya que existe un alumno registrado con la matricula ".$matricula;
        }
    } else {/* Modificar */  
        $sqlac = "CALL ACTUALIZARALUMNO(".$parametros["matricula"].",'".$parametros["nombre"]."','".$parametros["aPaterno"]."','".$parametros["aMaterno"]."','".$parametros["telefono"]."','".$parametros["grupo"]."','".$parametros["periodo"]."',".$activo.",".$parametros["id"].",'".$comentario."')";
        
        if ($query = $con->query($sqlac)) {
            echo "El alumno <b>" . $nombre . "</b> se modificó correctamente";
        } else {
            echo "Error: El alumno " . $nombre . " no se pudo registrar";
        }
    }
      
}

?>