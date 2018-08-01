<?php

include_once("../../Classes/Alumnos.class.php");
include_once("../../Classes/Catalogo2.class.php");

$obj = new Alumnos();
$catalogo = new Catalogo2();

if (isset($_POST['id']) && isset($_POST['erase'])) {/* Para eliminar el registro con el id recibido por get */
    $obj->setIdAlumno($_POST['id']);
    
    if ($obj->deleteRegistro()) {
        echo "El usuario se eliminó correctamente";
    } else {
        echo "El usuario no se pudo eliminar, ya que contiene datos asociados.";
    }
} else{
    if (isset($_POST['form'])) {
        $parametros = "";
        parse_str($_POST['form'], $parametros);
    }
    
    $obj->setMatricula($catalogo->satinizar_input($parametros['matricula']));
    $obj->setNombre($catalogo->satinizar_input($parametros['nombre']));
    $obj->setAMaterno($catalogo->satinizar_input($parametros['aMaterno']));
    $obj->setAPaterno($catalogo->satinizar_input($parametros['aPaterno']));
    $obj->setGrupo($catalogo->satinizar_input($parametros['grupo']));
    $obj->setPeriodo($catalogo->satinizar_input($parametros['periodo']));
    if (isset($parametros['activo']) && $parametros['activo'] == "on") {
        $obj->setActivo(1);
    } else {
        $obj->setActivo(0);
    }
    
    if (isset($parametros['id']) && empty($parametros['id'])) {/* Si el id esta vacio, hay que insertar un NUEVO registro */
        if ($obj->newRegistro()) {
            echo "El alumno <b>" . $obj->getNombre() . "</b> se registró correctamente";            
        } else {
            echo "Error: El alumno " . $obj->getNombre() . " no se pudo registrar";
        }
    } else {/* Modificar */  
        $obj->setIdAlumno($parametros['id']);
        
        if ($obj->editRegistro()) {
            echo "El alumno <b>" . $obj->getNombre() . "</b> se modificó correctamente";
        } else {
            echo "Error: El alumno " . $obj->getNombre() . " no se pudo registrar";
        }
    }
      
}

?>
