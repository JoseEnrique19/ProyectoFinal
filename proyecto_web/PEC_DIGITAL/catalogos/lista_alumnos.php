<?php
include_once("../WEB-INF/Classes/conexion.php");
$cabeceras = array("Matricula","Nombre","Activo","Editar","Borrar");
$alta = "catalogos/alta_alumnos.php";
$controlador = "WEB-INF/Controllers/Catalogos/Controller_Alumnos.php";
$same_page = "catalogos/lista_alumnos.php";
$mostrar = 0;
if (isset($_GET['mostrar'])) {
    $mostrar = $_GET['mostrar'];
}
if ($mostrar == 1) {
    $where = "WHERE p.idPersona = al.persona AND al.activo = 3";
}else{
    $where = "WHERE p.idPersona = al.persona AND al.activo != 3";
}
?>
     
<script type="text/javascript" language="javascript" src="resources/js/paginas/catalogos/lista_alumno.js"></script>
<img class="imagenMouse" width="30" height="30" src="resources/images/add.png" title="Nuevo" onclick='cambiarContenidosSinSesion("catalogos/alta_alumnos.php", "Nuevo Alumno");' style="float: right; cursor: pointer;" />

<br/><br/><br/>
<div>
    <div style="float: right;">
    <label for="checksc">Alumnos Borrados</label><input type="checkbox" id="checksc" value="1" <?php
    if ($mostrar == 1) {
        echo "checked";
    }
    ?>/>  
</div>
<table id="tAlumno" class="tAlumno">
    <thead>
        <tr>
            <?php
            for ($i = 0; $i < (count($cabeceras)); $i++) {
                echo "<th width=\"2%\" align=\"center\" scope=\"col\">" . $cabeceras[$i] . "</th>";
            }
            ?>                        
        </tr>
    </thead>
    <tbody>
        <?php
            $consulta = "SELECT CONCAT_WS(' ',p.nombre,p.aPaterno, p.aMaterno) AS Nombre, al.matricula AS Matricula, al.idAlumno AS IdAlumno,
                (CASE WHEN al.activo = 1 THEN 'Activo' WHEN al.activo = 3 THEN 'Borrado' ELSE 'Inactivo' END) AS Estado
            FROM alumnos AS al, personas AS p ". $where;
            $result = $con->query($consulta);
            while($rs = mysqli_fetch_array($result)){
                echo "<tr>";
                echo "<td width=\"2%\" align=\"center\" scope=\"row\">" . $rs['Matricula'] . "</td>";
                echo "<td width=\"2%\" align=\"center\" scope=\"row\">" . $rs['Nombre'] . "</td>";
                echo "<td width=\"2%\" align=\"center\" scope=\"row\">" . $rs['Estado'] . "</td>";
                echo "<td width=\"2%\" align=\"center\" scope=\"row\"><a href='#' onclick=\"cambiarContenidosEditando1Id('$alta', 'editar','".$rs['IdAlumno']."');return false;\" title='Editar'><img src=\"resources/images/Modify.png\" width=\"24\" height=\"24\"/></a></td>";
                echo "<td width=\"2%\" align=\"center\" scope=\"row\"><a href='#' onclick='eliminarRegistro1Id(\"$controlador\", \"$same_page\", \"".$rs['IdAlumno']."\");
                        return false;' title='Eliminar'><img src=\"resources/images/Erase.png\" width=\"24\" height=\"24\"/></a></td>";
                echo "</tr>";
            }
        ?>
    </tbody>
</table>