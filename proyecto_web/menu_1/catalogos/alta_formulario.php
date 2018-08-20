<?php

include_once("../WEB-INF/Classes/conexion.php");

$noRegistro = "";
$disabled = "";
$id = "";
$idFormulario = 1;
$idAsigAF = "";
$editar = false;

if(isset($_POST['id']) && $_POST['id'] != ""){
   $id = $_POST['id'];
   $valor = array();
   $consulta = "SELECT rp.noRegistro AS noRegistro, rp.orden AS orden, rp.respuesta AS respuesta FROM respuestas rp WHERE rp.asignacionAF = 1 AND rp.noRegistro =". $id;
   $queryValor = $con->query($consulta);
   while ($rsp = mysqli_fetch_array($queryValor)) {
        $valor[] = $rsp['respuesta'];
        $noRegistro = $rsp['noRegistro'];
    } 
    echo "noRegistro ". $noRegistro;
}else{
    $consulta_ultimo = "SELECT rp.noRegistro AS noRegistro FROM respuestas rp WHERE rp.asignacionAF = 1  GROUP BY rp.noRegistro DESC LIMIT 1";
    $query = $con->query($consulta_ultimo);
    while ($rs= mysqli_fetch_array($query)) {
        $noRegistro = $rs['noRegistro'];
    }
    $noRegistro = $noRegistro + 1;
    echo "noRegistro ". $noRegistro;   
}
?>
<script type="text/javascript" language="javascript" src="resources/js/paginas/catalogos/alta_formulario.js"></script>
<br/><br/>
<form id="formFormulario">
    <table style="width: 100%;">
        <?php 
            $consulta_campos = "SELECT af.idAsignacionAF AS idAsigAF, f.nombre AS NombreFormulario, ca.nombre AS Campo, ca.tipo AS tipo FROM campos ca INNER JOIN detalleformulario df ON df.campo = ca.idcampo
INNER JOIN formularios f ON df.formulario = f.idFormulario INNER JOIN asignacionaf af ON af.formulario = f.idFormulario WHERE f.idFormulario =". $idFormulario;
                $query = $con->query($consulta_campos);
                $i = 0;
                while ($rs = mysqli_fetch_array($query)) {
                    if (isset($_POST['id']) && $_POST['id'] != "") {
                        for ($j= $i; $j < (count($valor)) ; $j++) { 
                        echo "<tr>";
                            echo "<td style=\"width: 10%;\">".$rs['Campo']."</td>";
                            echo "<td style=\"width: 23%;\">";
                            if ($i == 2 || $i == 3) {
                                echo "<input type=\"".$rs['tipo']."\" style=\"width: 70%;\" class=\"redondea\" name=\"campo[".$i."]\" id=\"campo[".$i."]\" value=\"sin revision\" placeholder=\"escribe\" readonly=\"readonly\">";
                            }else{
                                echo "<input type=\"".$rs['tipo']."\" required=\"required\" style=\"width: 70%;\" class=\"redondea\" name=\"campo[".$i."]\" id=\"campo[".$i."]\" value=\"".$valor[$j]."\" placeholder=\"escribe\">";
                            }
                            echo "</td>";
                        echo "</tr>";
                        break;                 
                        }
                        $i++;
                        $idAsigAF = $rs['idAsigAF'];
                    }else{
                        echo "<tr>";
                            echo "<td style=\"width: 10%;\">".$rs['Campo']."</td>";
                            echo "<td style=\"width: 23%;\">";
                            if ($i == 2 || $i == 3) {
                                echo "<input type=\"".$rs['tipo']."\" style=\"width: 70%;\" class=\"redondea\" name=\"campo[".$i."]\" id=\"campo[".$i."]\" value=\"sin revision\" placeholder=\"escribe\" readonly =\"readonly\">";
                            }else{
                                echo "<input type=\"".$rs['tipo']."\" required=\"required\" style=\"width: 70%;\" class=\"redondea\" name=\"campo[".$i."]\" id=\"campo[".$i."]\" value=\"\" placeholder=\"escribe\">";
                            }
                            echo "</td>";
                        echo "</tr>";
                        $i++;
                        $idAsigAF = $rs['idAsigAF'];
                    }
                }

        ?>
        
    </table>
    <br/><br/>
    <input type="hidden" id="id" name="id" value="<?php echo $id?>">
    <input type="hidden" id="idFormulario" name="idFormulario" value="<?php echo $idFormulario?>">
    <input type="hidden" id="idAsigAF" name="idAsigAF" value="<?php echo $idAsigAF?>">
    <input type="hidden" id="noRegistro" name="noRegistro" value="<?php echo $noRegistro?>">
    <input type="submit" id="aceptar" class="boton" name="aceptar" value="Guardar"/>
    <input type="button" id="cancelar" class="boton" name="cancelar" value="Cancelar" onclick="cambiarContenidosSinSesion('catalogos/lista_registros.php', 'Formulario');"/>
</form>