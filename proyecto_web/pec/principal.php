<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user'] == "") {
    header("Location: index.php");
}
include_once("./WEB-INF/Classes/Catalogo2.class.php");
$catalogo = new Catalogo2();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>PEC</title>
        <link href="resources/Bootstrap/css/jquery-ui.css" rel="stylesheet" type="text/css" media="all">
        <link href="resources/css/principal.css" rel="stylesheet" type="text/css" media="all">
        
        <!-- Tables -->
        <link href="resources/css/table/datatables.min.css" rel="stylesheet" type="text/css" media="all">
        
        <!-- multiselect -->
        <link href="resources/css/multiselect/jquery.multiselect.css" rel="stylesheet" type="text/css">
        <link href="resources/css/multiselect/jquery.multiselect.filter.css" rel="stylesheet" type="text/css">
        
    </head>
    <body>
       <div class="cabecera" id="menu">
            <ul>
                <?php
                    $consulta_nivel ="SELECT cn.IdNivelUsuario, cu.Nombre FROM c_nivel_usuario cn INNER JOIN c_usuario cu ON cu.IdNivelUsuario = cn.IdNivelUsuario WHERE cu.NombreUsuario = '". $_SESSION['user']."'";
                    $res_cons_nivel = $catalogo->obtenerLista($consulta_nivel);
                    while ($nivel = mysql_fetch_row($res_cons_nivel)) {
                        if ($nivel[0] == 1) {
                            echo "<li><a onclick=\"cambiarContenidos('navegacion/mostrarDirectorio.php');\" style=\"cursor: pointer;\">Resumen</a></li>
                            <li><a onclick=\"cambiarContenidos('catalogos/lista_usuario.php');\" style=\"cursor: pointer;\">Administradores</a></li>
							<li><a onclick=\"cambiarContenidos('catalogos/lista_alumnos.php');\" style=\"cursor: pointer;\">Alumnos</a></li>
                            <li><a onclick=\"cambiarContenidos('catalogos/lista_supervisores.php');\" style=\"cursor: pointer;\">supervisores</a></li>
                            <li><a onclick=\"cambiarContenidos('catalogos/lista_nivel_usuario.php');\" style=\"cursor: pointer;\">Niveles de administrador</a></li>";
                        }
                    }
                ?>
                <li><a href="sesion.php?cerrar=1" style="cursor: pointer;">Cerrar sesión</a></li>
            </ul>
        </div>
        
        <div id="mensajes" title="Mensaje"></div>
        <div id="cargando" style="width:80%; margin-left: 50%; display: none; ">
            <img src="resources/images/cargando.gif"/>                          
        </div>
        <div id="loading_text" style="width:80%; margin-top: 5px;  margin-left: 45%;"></div>
        <div class="container" id="contenidos">
            
        </div>
        <div id="loading_text" style="width:80%; margin-top: 5px;  margin-left: 45%;"></div>
        <script src="resources/Bootstrap/js/jquery.js" type="text/javascript"></script>
        <script src="resources/Bootstrap/js/jquery.easing.1.3.js" type="text/javascript"></script>
        <script src="resources/Bootstrap/js/jquery-ui.js" type="text/javascript"></script>
        <script src="resources/Bootstrap/js/jquery.validate.js" type="text/javascript"></script>
        <script src="resources/Bootstrap/js/md5.js" type="text/javascript"></script>
        <script src="resources/js/table/datatables.min.js" type="text/javascript"></script>
        <script src="resources/js/multiselect/jquery.multiselect.min.js"></script>
        <script src="resources/js/multiselect/jquery.multiselect.filter.min.js"></script>
        <script src="resources/js/paginas/utilidades.js" type="text/javascript"></script>
    </body>
</html>

