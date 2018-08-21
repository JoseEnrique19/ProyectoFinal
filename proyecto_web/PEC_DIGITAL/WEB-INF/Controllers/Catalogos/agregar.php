<?php
include "conexion.php";
$matricula = "";
$nombre ="";
$aPaterno = "";
$aMaterno = "";
$telefono ="";
$grupo = "";
$periodo ="";
$activo = "checked";
if(!empty($_POST)){
	if(isset($_POST["matricula"]) &&isset($_POST["nombre"]) &&isset($_POST["aPaterno"]) &&isset($_POST["aMaterno"]) &&isset($_POST["telefono"]) &&isset($_POST["grupo"]) &&isset($_POST["periodo"]) &&isset($_POST["activo"])){
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
			$sql = "CALL ALUMNO(".$_POST["matricula"].",'".$_POST["nombre"]."','".$_POST["aPaterno"]."','".$_POST["aMaterno"]."','".$_POST["telefono"]."','".$_POST["grupo"]."','".$_POST["periodo"]."',".$activo.")";
			//$sql = "CALL ALUMNO(".$matricula.",'".$nombre."','".$aPaterno."','".$aMaterno."','".$telefono."','".$grupo."','".$periodo."',".$activo.");";
			echo $sql;
			$query = $con->query($sql);
			if($query!=null){
				print "<script>alert(\"Agregado exitosamente.\");window.location='../ver.php';</script>";
			}else{
				print "<script>alert(\"No se pudo agregar.\");window.location='../ver.php';</script>";

			}
		//}
	}
}



?>