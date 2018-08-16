<!DOCTYPE html>
<?php
include_once("./WEB-INF/Classes/conexion.php")
?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>MENU</title>
    <script src="resources/js/jquery-latest.js"></script>
    <script src="resources/js/menu.js"></script>
	<link rel="stylesheet" type="text/css" href="resources/css/fonts.css" rel="stylesheet"  media="all">
	<link rel="stylesheet" type="text/css" href="resources/css/estilos.css" rel="stylesheet"  media="all">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link href="resources/Bootstrap/css/jquery-ui.css" rel="stylesheet" type="text/css" media="all">
        
        <!-- Tables -->
        <!--<link href="resources/css/table/datatables.min.css" rel="stylesheet" type="text/css" media="all">-->
        
        <!-- multiselect -->
        <!--<link href="resources/css/multiselect/jquery.multiselect.css" rel="stylesheet" type="text/css">
        <link href="resources/css/multiselect/jquery.multiselect.filter.css" rel="stylesheet" type="text/css">-->
</head>
<body>
	<header>
		<div class="menu_bar">
			<a href="#" class="bt-menu"><span class="icon-align-justify"></span>Menú</a>
		</div>
		<nav>
			<ul>
                <?php
                    echo "
					<li><a onclick=\"cambiarContenidosSinSesion('catalogos/lista_alumnos.php');\" style=\"cursor: pointer;\"><span class=\"icon-user\"></span>Alumnos</a></li>
                    <li><a onclick=\"cambiarContenidosSinSesion('catalogos/lista_formularios.php');\" style=\"cursor: pointer;\">Formularios</a></li>";
                ?>
				
			</ul>
		</nav>
	</header>
<section>
	<div id="mensajes" title="Mensaje"></div>
        <div id="cargando" style="width:80%; margin-left: 50%; display: none; ">
            <img src="resources/images/cargando.gif"/>                          
        </div>
        <div id="loading_text" style="width:80%; margin-top: 5px;  margin-left: 45%;"></div>
        <div class="container" id="contenidos">
    </div>
</section>
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