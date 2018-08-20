<?php
include_once("../WEB-INF/Classes/conexion.php");
$cabeceras = array("Matricula","Nombre","Activo","Ver actividades");
$alta = "catalogos/lista_registros_supervisor.php";
$controlador = "WEB-INF/Controllers/Catalogos/Controller_Alumnos.php";
$same_page = "catalogos/lista_alumnos.php";
$mostrar = 0;
$idSupervisor = 1;
if (isset($_GET['mostrar'])) {
    $mostrar = $_GET['mostrar'];
}
if ($mostrar == 1) {
    $where = "WHERE p.idPersona = al.persona AND al.activo = 3";
}else{
    $where = "WHERE p.idPersona = al.persona AND al.activo != 3";
}
?>
     
<script type="text/javascript" language="javascript" src="resources/js/paginas/catalogos/lista_mis_alumnos.js"></script>
<!--<img class="imagenMouse" width="30" height="30" src="resources/images/add.png" title="Nuevo" onclick='cambiarContenidosSinSesion("catalogos/alta_alumnos.php", "Nuevo Alumno");' style="float: right; cursor: pointer;" />-->

<br/><br/><br/>
<!--<div>
    <div style="float: right;">
    <label for="checksc">Alumnos Borrados</label><input type="checkbox" id="checksc" value="1" <?php
    /*if ($mostrar == 1) {
        echo "checked";
    }*/
    ?>/>  
</div>-->
<table id="tAlumnos" class="tAlumno">
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
            $consulta = "SELECT asa.idAsignacionSA AS idASA, asa.supervisor AS idSupervisor, asa.alumno AS idAlumno, al.idAlumno AS IdAlumno, CONCAT_WS(' ',p.nombre,p.aPaterno, p.aMaterno) AS nombreAlumno, al.matricula AS Matricula, (CASE WHEN al.activo = 1 THEN 'Activo' WHEN al.activo = 3 THEN 'Borrado' ELSE 'Inactivo' END) AS Estado
            FROM asignacionsa asa INNER JOIN alumnos al ON asa.alumno = al.idAlumno INNER JOIN personas p ON p.idPersona = al.persona WHERE asa.supervisor =". $idSupervisor;
            /*$consulta = "SELECT CONCAT_WS(' ',p.nombre,p.aPaterno, p.aMaterno) AS Nombre, al.matricula AS Matricula, al.idAlumno AS IdAlumno,
                (CASE WHEN al.activo = 1 THEN 'Activo' WHEN al.activo = 3 THEN 'Borrado' ELSE 'Inactivo' END) AS Estado
            FROM alumnos AS al, personas AS p ". $where;*/
            $result = $con->query($consulta);
            while($rs = mysqli_fetch_array($result)){
                echo "<tr>";
                echo "<td width='2%' align='center' scope='row'><a onclick='cambiarContenidosSinSesion(\"catalogos/detalle_alumno.php?estudiante=".$rs['Matricula']."&id=".$rs['IdAlumno']."\")';>" . $rs['Matricula'] . "</a></td>";
                echo "<td width=\"2%\" align=\"center\" scope=\"row\">" . $rs['nombreAlumno'] . "</td>";
                echo "<td width=\"2%\" align=\"center\" scope=\"row\">" . $rs['Estado'] . "</td>";
                echo "<td width=\"2%\" align=\"center\" scope=\"row\"><a href='#' onclick=\"cambiarContenidosEditando1Id('$alta', 'editar','".$rs['idAlumno']."');return false;\" title='Ver actividades'><img src=\"resources/images/ver.png\" width=\"45\" height=\"25\"/></a></td>";
                echo "</tr>";
            }
        ?>
    </tbody>
</table>