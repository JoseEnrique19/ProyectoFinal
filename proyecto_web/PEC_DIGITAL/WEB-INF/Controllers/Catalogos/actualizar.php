<?php
include "conexion.php";
$matricula = "";
$nombre ="";
$aPaterno = "";
$aMaterno = "";
$telefono ="";
$grupo = "";
$periodo ="";
if(!empty($_POST)){
	if(isset($_POST["matricula"]) &&isset($_POST["nombre"]) &&isset($_POST["aPaterno"]) &&isset($_POST["aMaterno"]) &&isset($_POST["telefono"]) &&isset($_POST["grupo"]) &&isset($_POST["periodo"])){
		$matricula = $_POST['matricula'];
		$nombre = $_POST['nombre'];
		$aPaterno = $_POST['aPaterno'];
		$aMaterno = $_POST['aMaterno'];
		$telefono = $_POST['telefono'];
		$grupo = $_POST['grupo'];
		$periodo = $_POST['periodo'];
		if (isset($_POST['activo']) && $_POST['activo'] == "on") {
	        $activo = 1;
	    } else {
	        $activo = 0;
	    }
	    		//if($_POST["matricula"]!=""&& $_POST["nombre"]!=""&&$_POST[""]!=""){
			$sql = "CALL ACTUALIZARALUMNO(".$_POST["matricula"].",'".$_POST["nombre"]."','".$_POST["aPaterno"]."','".$_POST["aMaterno"]."','".$_POST["telefono"]."','".$_POST["grupo"]."','".$_POST["periodo"]."',".$activo.",".$_POST["idAlumno"].")";
			//$sql = "CALL ALUMNO(".$matricula.",'".$nombre."','".$aPaterno."','".$aMaterno."','".$telefono."','".$grupo."','".$periodo."',".$activo.");";
			echo $sql;
			$query = $con->query($sql);
			if($query!=null){
				print "<script>alert(\"Acualización exitosamente.\");window.location='../ver.php';</script>";
			}else{
				print "<script>alert(\"No se pudo actualizar.\");window.location='../ver.php';</script>";

			}
		//}
	}
}
?>