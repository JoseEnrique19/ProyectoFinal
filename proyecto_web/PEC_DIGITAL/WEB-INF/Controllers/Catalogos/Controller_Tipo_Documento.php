<?php

session_start();
if (!isset($_SESSION['user']) || $_SESSION['user'] == "") {
    header("Location: index.php");
}

include_once("../../Classes/TipoDocumento.class.php");
include_once("../../Classes/AtributosPlantilla.class.php");
include_once("../../Classes/Catalogo2.class.php");

$obj = new TipoDocumento();
$atributosPlantilla = new AtributosPlantilla();
$catalogo = new Catalogo2();

if (isset($_POST['id']) && isset($_POST['erase'])) {/* Para eliminar el registro con el id recibido por get */
    $obj->setIdTipoDocumento($_POST['id']);
    $atributosPlantilla->setIdTipoDocumento($obj->getIdTipoDocumento());
            
    if(!$atributosPlantilla->deleteByIdTipoDocumento()){
        echo "Error: No se han podido eliminar los datos de la plantilla del tipo de documento";
        return false;
    }
    
    if ($obj->deleteRegistro()) {
        echo "El tipo de documento se eliminó correctamente";
    } else {
        echo "El tipo de documento no se pudo eliminar, ya que contiene datos asociados.";
    }
} else{
    if (isset($_POST['form'])) {
        $parametros = "";
        parse_str($_POST['form'], $parametros);
    }
    
    $obj->setNombre($catalogo->satinizar_input($parametros['nombre']));
    if (isset($parametros['activo']) && $parametros['activo'] == "on") {
        $obj->setActivo(1);
    } else {
        $obj->setActivo(0);
    }
    if (isset($parametros['superior']) && !empty($parametros['superior'])) {
        $obj->setIdDocumentoSuperior($catalogo->satinizar_input($parametros['superior']));
    }
    $obj->setUsuarioCreacion($_SESSION['user']);
    $obj->setUsuarioUltimaModificacion($_SESSION['user']);
    $obj->setPantalla('PHP Controler Tipo Documento');
    
    if (isset($parametros['id']) && empty($parametros['id'])) {/* Si el id esta vacio, hay que insertar un NUEVO registro */
        if ($obj->newRegistro()) {
            echo "El tipo de documento <b>" . $obj->getNombre() . "</b> se registró correctamente";            
        } else {
            echo "Error: El tipo de documento " . $obj->getNombre() . " no se pudo registrar";
        }
    } else {/* Modificar */  
        $obj->setIdTipoDocumento($parametros['id']);
        $atributosPlantilla->setIdTipoDocumento($obj->getIdTipoDocumento());
        if(!$atributosPlantilla->deleteByIdTipoDocumento()){
            echo "Error: No se han podido eliminar los datos de la plantilla del tipo de documento";
            return false;
        }
        if ($obj->editRegistro()) {
            echo "El tipo de documento <b>" . $obj->getNombre() . "</b> se modificó correctamente";
        } else {
            echo "Error: El tipo de documento " . $obj->getNombre() . " no se pudo registrar";
        }
    }
    
    $campos = $_POST['campos'];     //Aquí tenemos todos los divs que tienen hijos y la cantidad de hijos que tienen

    $arrayCampos = split(",", $campos);
    $arrayIdCampo = array();
    
    $atributosPlantilla->setIdTipoDocumento($obj->getIdTipoDocumento());
    $atributosPlantilla->setPantalla("Controler Tipo Documento");
    $atributosPlantilla->setUsuarioCreacion($obj->getUsuarioCreacion());
    $atributosPlantilla->setUsuarioUltimaModificacion($obj->getUsuarioCreacion());
    foreach ($arrayCampos as $value) {
        if(!isset($parametros['campo_'.$value]) || empty($parametros['campo_'.$value])){
            continue;
        }
        if(strlen($value) == 1){    //No hay un atributo superior
            $atributosPlantilla->setIdAtributoSuperior(NULL);
        }else{  //Es hijo de algún otro atributo
            $padre = substr($value, 0, strlen($value) - 2);
            $atributosPlantilla->setIdAtributoSuperior($arrayIdCampo[$padre]);
        }
        $atributosPlantilla->setDescripcion($parametros['campo_'.$value]);
        if(!$atributosPlantilla->newRegistro()){
            echo "Error: No se pudo registrar el elemento ".$atributosPlantilla->getDescripcion().", intentelo de nuevo";
            return false;
        }
        $arrayIdCampo[$value] = $atributosPlantilla->getIdAtributoPlantilla(); 
    }
    echo "<br/>La plantilla se registró con éxito";
}
?>

