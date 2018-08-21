<?php
$estudiante=$_GET['estudiante'];
$idAlumno=$_GET['id'];
//$seccion='1';

include_once("../WEB-INF/Classes/conexion.php");

$id = "1715110333";

echo "<h1>Matricula: ".$estudiante."</h1>";

echo "<h1>Id: ".$idAlumno."</h1>";
/*$sql1= "select * from temas WHERE seccion='".$seccion."'";
    $query = $con->query($sql1);
    if($query->num_rows>0){
        echo "<center><div>Temas disponibles</div><h1><div>";
        while ($rs = mysqli_fetch_array($query)){
            $url="catalogos/formularios.php?tema=".$rs['idTema'];
            echo "<div><button class='boton' onclick='cambiarContenidosSinSesion(&quot;".$url."&quot;, &quot;Nuevo Alumno&quot;);'>".$rs['tema']."</button></div><br>"; 
        }
        echo "</div></h1></center>";
    }else{
        echo "<center><h1><div>No hay temas disponibles</div></h1></center>";
    }*/
?>

