<?php

include_once("../WEB-INF/Classes/conexion.php");

$matricula = "";
$nombre = "";
$aPaterno = "";
$aMaterno = "";
$telefono = "";
$grupo = "";
$periodo = "";
$activo = "checked";
$disabled = "";
$id = "";
$editar = false;
$periodos = array('Enero-Abril','Mayo-Agosto','Septiembre-Diciembre');

if(isset($_POST['id']) && $_POST['id'] != ""){

    $id = $_POST['id'];
    $user_id=null;
    $sql1= "SELECT * FROM personas INNER JOIN alumnos ON alumnos.persona = personas.idPersona WHERE idAlumno = ".$_POST["id"];
    $query = $con->query($sql1);
    $person = null;
    $activo = "checked";
    if($query->num_rows>0){
        while ($r=$query->fetch_object()){
            $person=$r;
        break;
        }
    }
 if($person->activo != "1"){
        $activo = "";
    }
    $matricula = $person->matricula;
    $nombre = $person->nombre;
    $aPaterno = $person->aPaterno;
    $aMaterno = $person->aMaterno;
    $telefono = $person->telefono;
    $grupo = $person->grupo;
    $periodo = $person->periodo;
    if($person->activo != "1"){
        $activo = "";
    }
    $disabled = "disabled";
    $editar = true;
}

?>
<script type="text/javascript" language="javascript" src="resources/js/paginas/catalogos/alta_alumno.js"></script>
<br/><br/>
<form id="formAlumno">
    <table style="width: 100%;">
        <tr>
            <td style="width: 10%;">Matricula</td>
            <td style="width: 23%;">
                <input type="number" required="required" style="width: 70%;" class="redondea" name="matricula" id="matricula" value="<?php echo $matricula;?>">
            </td>
        </tr>
        <tr>
            <td style="width: 10%;">Nombre</td>
            <td style="width: 23%;">
                <input type="text" srequired="required" style="width: 70%;" class="redondea" name="nombre" id="nombre" value="<?php echo $nombre; ?>"/>
            </td>
        </tr>
        <tr>
            <td style="width: 10%;">Apellido Paterno</td>
            <td style="width: 23%;">
                <input type="text" style="width: 70%;" class="redondea" name="aPaterno" id="aPaterno" value="<?php echo $aPaterno; ?>"/>
            </td>
        </tr>
        <tr>
            <td style="width: 10%;">Apellido Materno</td>
            <td style="width: 23%;">
                <input type="text" style="width: 70%;" class="redondea" name="aMaterno" id="aMaterno" value="<?php echo $aMaterno; ?>"/>
            </td>
        </tr>
        <tr>
            <td style="width: 10%;">Telefono</td>
            <td style="width: 23%;">
                <input type="number" style="width: 70%;" class="redondea" name="telefono" id="telefono" value="<?php echo $telefono; ?>"/>
            </td>
        </tr>
        <tr>
            <td style="width: 10%;">Grupo</td>
            <td style="width: 23%;">
                <input type="text" required="required" style="width: 70%;" class="redondea" name="grupo" id="grupo" value="<?php echo $grupo; ?>"/>
            </td>
        </tr>
        <tr>
            <td style="width: 10%;">Periodo</td>
            <td style="width: 23%;">
                <select id="periodo" name="periodo" required="required" style="width: 70%;" class="redondea">
                   <?php
                        echo "<option value=''>Selecciona una opción</option>";
                            foreach ($periodos as $valor) {
                                $s = '';
                                if($periodo == $valor){
                                    $s = 'selected';
                                }
                                echo "<option value= '$valor' $s> $valor</option>";
                            }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td style="width: 10%;">Activo</td>
            <td style="width: 23%;"><input type="checkbox" id="activo" name="activo" <?php echo $activo?>></td>
        </tr>
    </table>
    <br/><br/>
    <input type="hidden" id="id" name="id" value="<?php echo $id?>">
    <input type="submit" id="aceptar" class="boton" name="aceptar" value="Guardar"/>
    <input type="button" id="cancelar" class="boton" name="cancelar" value="Cancelar" onclick="cambiarContenidosSinSesion('catalogos/lista_alumnos.php', 'Alumno');"/>
</form>