<?php

session_start();
if (!isset($_SESSION['user']) || $_SESSION['user'] == "") {
    header("Location: index.php");
}
include_once("../WEB-INF/Classes/Alumnos.class.php");
include_once("../WEB-INF/Classes/Catalogo2.class.php");

$matricula = "";
$nombre = "";
$aPaterno = "";
$aMaterno = "";
$grupo = "";
$generacion = "";
$activo = "checked";
$disabled = "";
$catalogo = new Catalogo2();
$alumno = new Alumnos();
$id = "";
$editar = false;

if(isset($_POST['id']) && $_POST['id'] != ""){
    $id = $_POST['id'];
    $alumno->getRegistroById($id);
    $matricula = $alumno->getMatricula();
    $nombre = $alumno->getNombre();
    $aPaterno = $alumno->getAPaterno();
    $aMaterno = $alumno->getAMaterno();
    $grupo = $alumno->getGrupo();
    $generacion = $alumno->getGeneracion();
    if($alumno->getActivo() != "1"){
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
                <input type="text" style="width: 70%;" name="matricula" id="matricula" value="<?php echo $matricula;?>">
            </td>
            <td style="width: 10%;">Nombre</td>
            <td style="width: 23%;">
                <input type="text" style="width: 70%;" name="nombre" id="nombre" value="<?php echo $nombre; ?>"/>
            </td>
        <tr>
            <td style="width: 10%;">Apellido Paterno</td>
            <td style="width: 23%;">
                <input type="text" style="width: 70%;" name="aPaterno" id="aPaterno" value="<?php echo $aPaterno; ?>"/>
            </td>
            <td style="width: 10%;">Apellido Materno</td>
            <td style="width: 23%;">
                <input type="text" style="width: 70%;" name="aMaterno" id="aMaterno" value="<?php echo $aMaterno; ?>"/>
            </td>
        </tr>
        <tr>
            <td style="width: 10%;">Grupo</td>
            <td style="width: 23%;">
                <input type="text" style="width: 70%;" name="grupo" id="grupo" value="<?php echo $grupo; ?>"/>
            </td>
            <td style="width: 10%;">Generación</td>
            <td style="width: 23%;">
                <input class="generacion" style="width: 70%;" name="generacion" id="generacion" value="<?php echo $generacion; ?>"/>
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
    <input type="button" id="cancelar" class="boton" name="cancelar" value="Cancelar" onclick="cambiarContenidos('catalogos/lista_alumnos.php', 'Alumno');"/>
</form>