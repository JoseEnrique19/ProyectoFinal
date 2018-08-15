<?php
	# conectare la base de datos
    $con=@mysqli_connect("localhost", "root", "", "test");
    if(!$con){
        die("imposible conectarse: ".mysqli_error($con));
    }
    if (@mysqli_connect_errno()) {
        die("Connect failed: ".mysqli_connect_errno()." : ". mysqli_connect_error());
    }
	
	$productos = fopen ("products.csv" , "r" );//leo el archivo que contiene los datos del producto
while (($datos =fgetcsv($productos,1000,",")) !== FALSE )//Leo linea por linea del archivo hasta un maximo de 1000 caracteres por linea leida usando coma(,) como delimitador
{
 $linea[]=array('codigo'=>$datos[0],'descripcion'=>$datos[1],'fabricante'=>$datos[2],'precio'=>$datos[3]);//Arreglo Bidimensional para guardar los datos de cada linea leida del archivo
}
fclose ($productos);//Cierra el archivo

	$ingresado=0;//Variable que almacenara los insert exitosos
	$error=0;//Variable que almacenara los errores en almacenamiento
	$duplicado=0;//Variable que almacenara los registros duplicados
    foreach($linea as $indice=>$value) //Iteracion el array para extraer cada uno de los valores almacenados en cada items
	{
	$codigo=$value["codigo"];//Codigo del producto
	$descripcion=$value["descripcion"];//descripcion del producto
	$fabricante=$value["fabricante"];//fabricante del producto
	$precio=$value["precio"];//precio del producto
	
	$sql=mysqli_query($con,"select * from productos where codigo='$codigo'");//Consulta a la tabla productos
	$num=mysqli_num_rows($sql);//Cuenta el numero de registros devueltos por la consulta
	if ($num==0)//Si es == 0 inserto
	{
	if ($insert=mysqli_query($con,"insert into productos (codigo, descripcion, fabricante, precio) values('$codigo','$descripcion','$fabricante','$precio')"))
	{
	echo $msj='<font color=green>Producto <b>'.$descripcion.'</b> Guardado</font><br/>';
	$ingresado+=1;
	}//fin del if que comprueba que se guarden los datos
	else//sino ingresa el producto
	{
	echo $msj='<font color=red>Producto <b>'.$codigo.' </b> NO Guardado '.mysqli_error().'</font><br/>';
	$error+=1;
	}
	}//fin de if que comprueba que no haya en registro duplicado
	else
	{
	$duplicado+=1;
	echo $duplicate='<font color=red>El Producto codigo <b>'.$codigo.'</b> Esta duplicado<br></font>';
	}
	}
	echo "<font color=green>".number_format($ingresado,2)." Productos Almacenados con exito<br/>";
	echo "<font color=red>".number_format($duplicado,2)." Productos Duplicados<br/>";
	echo "<font color=red>".number_format($error,2)." Errores de almacenamiento<br/>";
	?>