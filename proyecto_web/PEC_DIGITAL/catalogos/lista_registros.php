<?php
include_once("../WEB-INF/Classes/conexion.php");
//$cabeceras = array("Matricula","Nombre","Activo","Editar","Borrar");
$cabeceras = array();
$alta = "catalogos/alta_formulario.php";
$controlador = "WEB-INF/Controllers/Catalogos/Controller_Alumnos.php";
$same_page = "catalogos/lista_alumnos.php";
$mostrar = 0;
$consulta_cabecera = "SELECT af.idAsignacionAF AS idAsigAF, f.nombre AS NombreFormulario, ca.nombre AS Campo, ca.tipo AS tipo FROM campos ca INNER JOIN detalleformulario df ON df.campo = ca.idcampo
INNER JOIN formularios f ON df.formulario = f.idFormulario INNER JOIN asignacionaf af ON af.formulario = f.idFormulario WHERE f.idFormulario = 1";
 $query = $con->query($consulta_cabecera);
    
while($row = mysqli_fetch_array($query)) { 
    $cabeceras[] = $row['Campo'];
}  
if (isset($_GET['mostrar'])) {
    $mostrar = $_GET['mostrar'];
}
if ($mostrar == 1) {
    $where = "WHERE p.idPersona = al.persona AND al.activo = 3";
}else{
    $where = "WHERE p.idPersona = al.persona AND al.activo != 3";
}
?>
     
<script type="text/javascript" language="javascript" src="resources/js/paginas/catalogos/lista_registros.js"></script>
<img class="imagenMouse" width="30" height="30" src="resources/images/add.png" title="Nuevo" onclick='cambiarContenidosSinSesion("catalogos/alta_formulario.php", "Nuevo Registro");' style="float: right; cursor: pointer;" />

<br/><br/><br/>
<table id="tRegistros" class="tAlumno">
    <thead>
        <tr>
            <?php
            for ($i = 0; $i < (count($cabeceras)); $i++) {
                echo "<th width=\"2%\" align=\"center\" scope=\"col\">" . $cabeceras[$i] . "</th>";
            }
            echo "<th width=\"2%\" align=\"center\" scope=\"col\">Editar</th>";
            echo "<th width=\"2%\" align=\"center\" scope=\"col\">Borrar</th>";
            ?>                        
        </tr>
    </thead>
    <tbody>
        <?php
            $noRegistros= "SELECT rp.noRegistro AS noRegistro FROM respuestas rp WHERE rp.asignacionAF = 1  GROUP BY rp.noRegistro";
            $rwRegistros= $con->query($noRegistros);
            $vuelta = 4;
            while($rs = mysqli_fetch_array($rwRegistros)){
                $respuestas = array();
                $linea = $rs['noRegistro'];
                //echo "vuelta ". $rs['noRegistro']. "<br/> \n";
                $consulta = "SELECT rp.noRegistro AS noRegistro, rp.orden AS orden, rp.respuesta AS respuesta FROM respuestas rp WHERE rp.asignacionAF = 1 AND rp.noRegistro =". $linea;
                $result = $con->query($consulta);
                while($row = mysqli_fetch_array($result)) { 
                    $respuestas[] = $row['respuesta'];  
                }
                    echo "<tr>";
                    for ($i = 0; $i < $vuelta; $i++) {
                         echo "<td width=\"2%\" align=\"center\" scope=\"row\">" . $respuestas[$i] . "</td>";
                    }
                            echo "<td width=\"2%\" align=\"center\" scope=\"row\"><a href='#' onclick=\"cambiarContenidosEditando1Id('$alta', 'editar','".$linea."');return false;\" title='Editar'><img src=\"resources/images/Modify.png\" width=\"24\" height=\"24\"/></a></td>";
                            echo "<td width=\"2%\" align=\"center\" scope=\"row\"><a href='#' onclick='eliminarRegistro1Id(\"$controlador\", \"$same_page\", \"".$linea."\");
                                    return false;' title='Eliminar'><img src=\"resources/images/Erase.png\" width=\"24\" height=\"24\"/></a></td>";
                            echo "</tr>";
            }
        ?>
    </tbody>
</table>