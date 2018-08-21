<?php
$seccion=$_GET['seccion'];
//$seccion='1';

include_once("../WEB-INF/Classes/conexion.php");

$id = "1715110333";

$sql1= "select * from temas WHERE seccion='".$seccion."'";
    $query = $con->query($sql1);
    if($query->num_rows>0){
        echo "<center><h1><div>Temas disponibles</div><div>";
        while ($rs = mysqli_fetch_array($query)){
            $url="catalogos/formularios.php?tema=".$rs['idTema'];
            echo "<div><button class='boton' onclick='cambiarContenidosSinSesion(&quot;".$url."&quot;, &quot;Nuevo Formulario&quot;);'>".$rs['tema']."</button></div><br>"; 
        }
        echo "</div></h1></center>";
    }else{
        echo "<center><h1><div>No hay temas disponibles</div></h1></center>";
    }
?>

