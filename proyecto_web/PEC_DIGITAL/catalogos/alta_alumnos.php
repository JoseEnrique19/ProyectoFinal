<?php

include_once("../WEB-INF/Classes/conexion.php");

$matricula = "";
$nombre = "";
$aPaterno = "";
$aMaterno = "";
$telefono = "";
$grupo = "";
$periodo = "";
$comentario = "*Sin estado*";
$activo = "checked";
$disabled = "";
$id = "";
$editar = false;
$periodos = array('Enero-Abril','Mayo-Agosto','Septiembre-Diciembre');

if(isset($_POST['id']) && $_POST['id'] != ""){
    $comentario = "";
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
    $comentario = $person->comentario;
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
                <input type="number" required="required" style="width: 70%;" class="redondea" name="matricula" id="matricula" value="<?php echo $matricula;?>" placeholder="Solo ingresa numero. ej: 1715110007">
            </td>
        </tr>
        <tr>
            <td style="width: 10%;">Nombre</td>
            <td style="width: 23%;">
                <input type="text" required="required" style="width: 70%;" class="redondea" name="nombre" id="nombre" pattern="[A-Za-z -A-Za-z-éÉ-áÁ-íÍ-óÓ-úÚ]{4,15}" title="Solo debe ingresar letras, mayusculas y minusculas. ejemplo: Mateo " value="<?php echo $nombre; ?>" placeholder="ej: Juan"/>
            </td>
        </tr>
        <tr>
            <td style="width: 10%;">Apellido Paterno</td>
            <td style="width: 23%;">
                <input type="text" style="width: 70%;" class="redondea" name="aPaterno" id="aPaterno" pattern="[A-Za-z-éÉ-áÁ-íÍ-óÓ-úÚ]{4,15}" title="Solo debe ingresar letras, mayusculas y minusculas. ejemplo: Olvera " value="<?php echo $aPaterno; ?>" placeholder="ej: Hernan"/>
            </td>
        </tr>
        <tr>
            <td style="width: 10%;">Apellido Materno</td>
            <td style="width: 23%;">
                <input type="text" style="width: 70%;" class="redondea" name="aMaterno" id="aMaterno" pattern="[A-Za-z-éÉ-áÁ-íÍ-óÓ-úÚ]{4,15}" title="Solo debe ingresar letras, mayusculas y minusculas. ejemplo: Olvera " value="<?php echo $aMaterno; ?>" placeholder="ej: Hernan"/>
            </td>
        </tr>
        <tr>
            <td style="width: 10%;">Telefono</td>
            <td style="width: 23%;">
                <input type="number" style="width: 70%;" class="redondea" name="telefono" id="telefono" value="<?php echo $telefono; ?>" placeholder="Solo 10 digitos ej: 7751234567"/>
            </td>
        </tr>
        <tr>
            <td style="width: 10%;">Grupo</td>
            <td style="width: 23%;">
                <input type="text" required="required" style="width: 70%;" class="redondea" pattern="[A-Z]{3,5}[0-9]{1,4}" title="El formato debe coincidir con 3 a 5 letras mayúsculas y 2 a 4 números. ejemplo: ITIC92" name="grupo" id="grupo" value="<?php echo $grupo; ?>" placeholder="ej: AAAA##"/>
            </td>
        </tr>
        <tr>
            <td style="width: 10%;">Periodo Escolar</td>
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
        <tr>
            <td style="width: 10%;"><label id="coment">Comentario</label></td>
            <td style="width: 23%;"><input type="textarea" id="comentario" name="comentario" style="width: 70%;" class="redondea" value="<?php echo $comentario; ?>" placeholder="Comentario"></td>
        </tr>
    </table>
    <br/><br/>
    <input type="hidden" id="id" name="id" value="<?php echo $id?>">
    <input type="submit" id="aceptar" class="boton" name="aceptar" value="Guardar"/>
    <input type="button" id="cancelar" class="boton" name="cancelar" value="Cancelar" onclick="cambiarContenidosSinSesion('catalogos/lista_alumnos.php', 'Alumno');"/>
</form>