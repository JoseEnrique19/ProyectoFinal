<?php
include_once("WEB-INF/Classes/Session.class.php");
include_once("WEB-INF/Classes/Catalogo2.class.php");

if (isset($_GET['cerrar']) && $_GET['cerrar'] == '1') { /* Cerrar sesion */
    
    setcookie("user","",time()-1);
    setcookie("idUsuario","",time()-1);
    setcookie("IdSession","",time()-1);
    
    session_start();
    session_destroy();
    header("Location: index.php");
return;
  
}else{
    $index = "index.php";
    /* Si se recibe por post el username y password y son diferente a una cadena vacia */
    if (isset($_POST['username']) && $_POST['username'] != "" && isset($_POST['password']) && $_POST['password'] != "") {  
        ini_set("session.gc_maxlifetime", 7200);
        session_start();
         if (stripos($_POST['username'], "DELETE") !== false ||
                stripos($_POST['username'], "INSERT") !== false ||
                stripos($_POST['username'], "UPDATE") !== false ||
                stripos($_POST['password'], "DELETE") !== false ||
                stripos($_POST['password'], "INSERT") !== false ||
                stripos($_POST['password'], "UPDATE")
        ) {//Si tiene alguna palabra reservada de mysql
            header("Location: $index?session=2");
	return;
        }else{
            $session = new Session(); 
            $catalogo = new Catalogo2();
            $tiempo_cookies = time()+3600 * 24 * 365;
            if ($session->getLogin($_POST['username'], $_POST['password'])) {//Buscamos el id del usuario dentro de la base de la empresa
                $_SESSION['user'] = $_POST['username'];
                $_SESSION['idUsuario'] = $session->getId_usu();
                $_SESSION['IdSession'] = $catalogo->generaPass(15);
                
                setcookie("user", $_POST['username'], $tiempo_cookies);
                setcookie("idUsuario", $session->getId_usu(), $tiempo_cookies);
                setcookie("IdSession", $_SESSION['IdSession'], $tiempo_cookies);
                header("Location: principal.php");
                return;
            }else{
                header("Location: $index?session=1");
		return;
            }
        }
    }
}
?>