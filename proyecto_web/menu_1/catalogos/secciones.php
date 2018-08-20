<?php

include_once("../WEB-INF/Classes/conexion.php");

$id = "1715110333";

$sql1= "select * from secciones";
    $query = $con->query($sql1);
    if($query->num_rows>0){
        echo "<center><h1><div>Secciones disponibles</div><div><br>";
        while ($rs = mysqli_fetch_array($query)){
            $url="catalogos/temas.php?seccion=".$rs['idSeccion'];
            echo "<div><button class='boton' onclick='cambiarContenidosSinSesion(&quot;".$url."&quot;, &quot;Nuevo Alumno&quot;);'>".$rs['nombre']."</button></div><br>";   
        }
        echo "</div></h1></center>";
    }else{
        echo "<center><h1><div>No hay Secciones disponibles</div></h1></center>";
    }
?>

