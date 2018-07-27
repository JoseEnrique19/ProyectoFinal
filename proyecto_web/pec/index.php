<?php
session_start();
if ( isset($_COOKIE['user']) && isset($_COOKIE['IdSession']) && strlen($_COOKIE['IdSession']) == 15) {
    $_SESSION['user'] = $_COOKIE["user"];
    $_SESSION['idUsuario'] = $_COOKIE["idUsuario"];               
    $_SESSION['IdSession'] = $_COOKIE["IdSession"];     
    header("Location: principal.php");    
}

$mensaje = "";
$hidden = "";
if(isset($_GET['session'])){
    $hidden = "<input type='hidden' name='mensaje' id='mensaje' value='1'/>";
    switch ($_GET['session']) {
        case "1":
            $mensaje = "Usuario y/o password incorrectos";
            break;                    
        case "2":
            $mensaje = "Atención, no se permite usar palabras reservadas del sistema";
            break;
        case "3":
            $mensaje = "Atención, en el usuario sólo se permite letras y números";
            break;
        case "4":
            $mensaje = "Atención, su sesión ha caducado, vuelve a iniciar una nueva";
            break;
        case "5":
            $mensaje = "No se ha podido iniciar la sesión correctamente, intenta de nuevo por favor";
            break;
        case "6":
            $mensaje = "Ya hay otra sesión iniciada de ValleW, para iniciar con otra cuenta cierra la sesión anterior <a href='principal.php'>aquí</a>";
            break;
        default:
            $mensaje = "";
            break;
    }
}else{
    $mensaje = "";
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>PEC</title>
        <link href="resources/Bootstrap/css/jquery-ui.css" rel="stylesheet" type="text/css" media="all">
        <link href="resources/css/index.css" rel="stylesheet">               
    </head>
    <body>
        <div class="container">
            <section id="content">
                    <form id="frmLogin" name="frmLogin" method="POST" action="<?php echo htmlspecialchars("sesion.php");?>" onsubmit="javascript:Encriptar();">
                        <h1>Inicio de sesión</h1>
                        <div>
                            <input type="text" placeholder="Usuario" id="username" name="username" />
                        </div>
                        <div>
                            <input type="password" placeholder="Contraseña" id="password" name="password" />
                        </div>
                        <div>
                            <input id="guardar" name="guardar" type="submit" value="Entrar" />
                        </div>
                        <?php echo $hidden; ?>
                    </form><!-- form -->
            </section><!-- content -->
        </div><!-- container -->
        <script src="resources/Bootstrap/js/jquery.js" type="text/javascript"></script>
        <script src="resources/Bootstrap/js/jquery.easing.1.3.js" type="text/javascript"></script>
        <script src="resources/Bootstrap/js/jquery-ui.js" type="text/javascript"></script>
        <script src="resources/Bootstrap/js/jquery.validate.js" type="text/javascript"></script>
        <script src="resources/Bootstrap/js/md5.js" type="text/javascript"></script>
        <script src="resources/js/paginas/index.js" type="text/javascript"></script>
        
        <div id="dialog" title="Aviso">
            <p><?php echo $mensaje; ?></p>
        </div>
    </body>
</html>
