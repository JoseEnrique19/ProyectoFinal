<?php
session_start();

if ((!isset($_SESSION['user']) || $_SESSION['user'] == "") || (!isset($_SESSION['IdSession']) || strlen($_SESSION['IdSession']) != 15)) {
    if(isset($_COOKIE["idEmpresa"])){
        session_destroy();
        session_start();

        $_SESSION['user'] = $_COOKIE["user"];
        $_SESSION['idUsuario'] = $_COOKIE["idUsuario"];               
        $_SESSION['IdSession'] = $_COOKIE["IdSession"];     

        echo "";
    }else{
        echo "false";
    }
}else{
    echo "";
}
?>
