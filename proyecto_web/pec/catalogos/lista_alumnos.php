<?php

session_start();

if (!isset($_SESSION['user']) || $_SESSION['user'] == "") {
    header("Location: index.php");
}
include_once("../WEB-INF/Classes/Catalogo2.class.php");
$cabeceras = array("Matricula","Nombre","Activo","","");
$alta = "catalogos/alta_alumnos.php";
$controlador = "WEB-INF/Controllers/Catalogos/Controller_Alumnos.php";
$same_page = "catalogos/lista_alumnos.php";
?>

<script type="text/javascript" language="javascript" src="resources/js/paginas/catalogos/lista_alumno.js"></script>
<img class="imagenMouse" src="resources/images/add.png" title="Nuevo" onclick='cambiarContenidos("catalogos/alta_alumnos.php", "Nuevo Alumno");' style="float: right; cursor: pointer;" />

<br/><br/><br/>

<table id="tAlumno">
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
            $catalogo = new Catalogo2();
            /* Esta consulta no muestra los alumnos que son 'borrados del sistema'
            $consulta = "SELECT CONCAT_WS(' ',al.Nombre,al.APaterno,al.AMaterno) AS Nombre, al.Matricula AS Matricula, al.IdAlumno AS IdAlumno,
                (CASE WHEN al.Activo = 1 THEN 'Activo' WHEN al.Activo = 3 THEN 'Borrado' ELSE 'Inactivo' END) AS Estado
		FROM alumnos AS al WHERE al.Activo != 3";*/
            $consulta = "SELECT CONCAT_WS(' ',al.Nombre,al.APaterno,al.AMaterno) AS Nombre, al.Matricula AS Matricula, al.IdAlumno AS IdAlumno,
                (CASE WHEN al.Activo = 1 THEN 'Activo' WHEN al.Activo = 3 THEN 'Borrado' ELSE 'Inactivo' END) AS Estado
            FROM alumnos AS al";
            $result = $catalogo->obtenerLista($consulta);
            while($rs = mysql_fetch_array($result)){
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