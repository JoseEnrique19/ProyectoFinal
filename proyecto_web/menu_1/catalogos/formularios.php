<?php
$tema = $_GET['tema'];
//$seccion='1';
echo "recibo ". $tema;
include_once("../WEB-INF/Classes/conexion.php");

$id = "1715110333";

$sql1= "SELECT * from formularios WHERE tema='".$tema."'";
    $query = $con->query($sql1);
    if($query->num_rows>0){
        echo "<center><h1><div>Formularios disponibles</div><div>";
        while ($rs = mysqli_fetch_array($query)){
            //Cambia la url para que le envie la variable del formulario seleccionado
            $url="catalogos/lista_registros.php?form=".$rs['idFormulario'];
            
            echo "<div><button class='boton' onclick='cambiarContenidosSinSesion(&quot;".$url."&quot;, &quot;Nuevo Alumno&quot;);'>".$rs['nombre']."</button></div><br>"; 
        }
        echo "</div></h1></center>";
    }else{
        echo "<center><h1><div>No hay temas disponibles</div></h1></center>";
    }
?>

