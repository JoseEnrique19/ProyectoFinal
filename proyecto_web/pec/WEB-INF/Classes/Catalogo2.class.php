<?php

include_once("Conexion2.class.php");
include_once("Alumnos.class.php");
/**
 * Description of Catalogo2
 *
 * @author DDHL
 */
class Catalogo2 {

    private $empresa;
    private $log;
    private $tipo;
    private $tabla;
    private $accion;

    public function multiQuery($consultas) {
        $array_consultas = split(";", $consultas);
        $conexion = new Conexion2();
        if (isset($this->empresa)) {
            $conexion->setEmpresa($this->empresa);
        }
        $conexion->Conectar();
        $resultado = "1";
        foreach ($array_consultas as $consulta) {
            if ($consulta == "") {
                continue;
            }
            echo "Ejecutando: $consulta ...<br/>";
            $resultado = $conexion->Ejecutar($consulta);
            echo "Respuesta: $resultado<br/><br/>";
            if ($resultado != "1") {
                break;
            }
            /* Guardamos los queries que se ejecutan (INSERT, DELETE y UPDATE) */
            $this->preparaLog($consulta);

            if ($this->log) {//Si se va a registrar un log
                if (isset($_SESSION['idUsuario'])) {
                    $usuario = $_SESSION['idUsuario'];
                } else {
                    /* Obtenemos el usuario que se pone por default segun los parametros globales */
                    $usuario = "1";
                }
                $sesion = "";
                if(isset($_SESSION['IdSession'])){
                    $sesion = $_SESSION['IdSession'];
                }
                $consulta = "INSERT INTO c_log(IdQuery, Consulta, Fecha, IdUsuario, Tipo, Sesion) VALUES(0,'" . str_replace("'", "´", $consulta) . "',NOW(),$usuario,'$this->tipo','$sesion');";
                $conexion->Ejecutar($consulta);
            }
        }
        $conexion->Desconectar();
        return $resultado;
    }

    public function obtenerLista($consulta) {
        $conexion = new Conexion2();
        if (isset($this->empresa)) {
            $conexion->setEmpresa($this->empresa);            
        }
        $conexion->Conectar();
        $query = $conexion->Ejecutar($consulta);      
        /* Guardamos los queries que se ejecutan (INSERT, DELETE y UPDATE) */
        $this->preparaLog($consulta);

        if ($this->log) {//Si se va a registrar un log
            if (isset($_SESSION['idUsuario'])) {
                $usuario = $_SESSION['idUsuario'];
            } else {
                /* Obtenemos el usuario que se pone por default segun los parametros globales */
                $usuario = "1";
            }
            $sesion = "";
            if(isset($_SESSION['IdSession'])){
                $sesion = $_SESSION['IdSession'];
            }
            $consulta = "INSERT INTO c_log(IdQuery, Consulta, Fecha, IdUsuario, Tipo, Sesion) VALUES(0,'" . str_replace("'", "´", $consulta) . "',NOW(),$usuario,'$this->tipo','$sesion');";
            $conexion->Ejecutar($consulta);
        }
        $conexion->Desconectar();
        return $query;
    }

    public function ejecutaConsultaActualizacion($consulta, $tabla, $where) {
        $estado = $this->obtenerEstadoAnterior($tabla, $where);
        $estado2 = $this->obtieneDatosAnteriores($tabla, $where);
        $conexion = new Conexion2();
        if (isset($this->empresa)) {
            $conexion->setEmpresa($this->empresa);
        }
        
        $conexion->Conectar();
        $query = $conexion->Ejecutar($consulta);  
        if($query != "0"){
            /* Guardamos los queries que se ejecutan (INSERT, DELETE y UPDATE) */
            $this->preparaLog($consulta);

            if ($this->log) {//Si se va a registrar un log
                if (isset($_SESSION['idUsuario'])) {
                    $usuario = $_SESSION['idUsuario'];
                } else {
                    /* Obtenemos el usuario que se pone por default segun los parametros globales */
                    $usuario = "1";
                }
                $sesion = "";
                if(isset($_SESSION['IdSession'])){
                    $sesion = $_SESSION['IdSession'];
                }
//                $consulta = "INSERT INTO c_log(IdQuery, Consulta, Fecha, IdUsuario, Tipo, Sesion) VALUES(0,'" . str_replace("'", "´", $consulta) . "',NOW(),$usuario,'$this->tipo','$sesion');";
//                $conexion->Ejecutar($consulta);
//                $idLog = mysql_insert_id();
                $whereExploded = explode("=", $where);
                $aux = "";
                if(isset($whereExploded[1])){
                       $aux = trim($whereExploded[1]," ");
                }
                $idLog = $this->insertaRegistroLog($consulta, $usuario, $where, $aux,$estado);
                $conexion->Conectar();
                $conexion->Ejecutar("INSERT INTO c_loganterior(IdEstadoAnterior, IdQuery, EstadoAnterior,DatosAnteriores) VALUES(0,$idLog,'$estado','$estado2');");
            }            
        }
        $conexion->Desconectar();
        return $query;
    }
    
    private function obtenerEstadoAnterior($tabla, $where){        
        $conexion = new Conexion2();
        if (isset($this->empresa)) {
            $conexion->setEmpresa($this->empresa);
        }
        $conexion->Conectar();
        
        $estado = "";        
        /*Obtenemos el nombre de la columna*/
        $consulta = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='".$conexion->getMYSQL_DB()."' AND `TABLE_NAME`='$tabla';";                
        $result = $conexion->Ejecutar($consulta);
        $conexion->Desconectar();
        if(mysql_num_rows($result) > 0){            
            $nombre_columnas = array();
            while($rs = mysql_fetch_array($result)){
                array_push($nombre_columnas, $rs['COLUMN_NAME']);                
            }
            $consulta = "SELECT * FROM $tabla WHERE $where";
            $result = $this->obtenerLista($consulta);
            if(mysql_num_rows($result) > 0){
                while($rs = mysql_fetch_array($result)){
                    foreach ($nombre_columnas as $value) {                        
                        $estado .= ($value." = ".$rs[$value].", ");
                    }
                }
                $estado = substr($estado, 0, strlen($estado)-1);                
            }
        }
        return $estado;
    }
    
    public function obtieneDatosAnteriores($tabla, $where){
        $conexion = new Conexion2();
        if (isset($this->empresa)) {
            $conexion->setEmpresa($this->empresa);
        }
        $conexion->Conectar();        
        $estado = "";        
        /*Obtenemos el nombre de la columna*/
        $consulta = "SELECT `COLUMN_NAME`, LOWER(COLUMN_COMMENT) AS COLUMN_COMMENT FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='".$conexion->getMYSQL_DB()."' AND `TABLE_NAME`='$tabla';";                
        $result = $conexion->Ejecutar($consulta);
        $conexion->Desconectar();
        if(mysql_num_rows($result) > 0){            
            $nombre_columnas = array();
            while($rs = mysql_fetch_array($result)){
//                array_push($nombre_columnas, $rs['COLUMN_NAME']);
                if((strpos($rs['COLUMN_COMMENT'], "&&&")) !== false){//Si el comentario no viene con &&&, entonces lo ignoramos.
                    $columna = explode("&&&", $rs['COLUMN_COMMENT']);
                    if($columna[0] != ""){
                        $nombre_columnas[$rs['COLUMN_NAME']] = $columna[0];
                    } 
                }                               
            }
            $consulta = "SELECT * FROM $tabla WHERE $where";
            $result = $this->obtenerLista($consulta);
            if(mysql_num_rows($result) > 0){
                while($rs = mysql_fetch_array($result)){
                    foreach ($nombre_columnas as $campo => $nombreCampo) {                        
                        //$estado .= ($value." = ".$rs[$value].", ");
                        if($nombreCampo != "" && $rs[$campo] != ""){
                            $estado .= "$nombreCampo = " . $rs[$campo] . ", ";
                        }                        
                    }
                }
                //$estado = substr($estado, 0, strlen($estado)-1);                
            }
        }
        return trim($estado,", ");
    }

    public function insertarRegistro($consulta) {
        $conexion = new Conexion2();
        if (isset($this->empresa)) {
            $conexion->setEmpresa($this->empresa);
        }
        $conexion->Conectar();
        $query = $conexion->Ejecutar($consulta);
        $id = mysql_insert_id();
        if($id!=NULL && $id!=0){
            /* Guardamos los queries que se ejecutan (INSERT, DELETE y UPDATE) */
            $this->preparaLog($consulta);

            if ($this->log) {//Si se va a registrar un log
                if (isset($_SESSION['idUsuario'])) {
                    $usuario = $_SESSION['idUsuario'];
                } else {
                    /* Obtenemos el usuario que se pone por default segun los parametros globales */
                    $usuario = "1";
                }
                $this->insertaRegistroLog($consulta, $usuario, "", $id,"");
            }            
        }
        $conexion->Desconectar();
        return $id;
    }
    
    public function insertaRegistroLog($query, $usuario, $where, $id, $datosAnteriores){        
        $sesion = "";
        if(isset($_SESSION['IdSession'])){
            $sesion = $_SESSION['IdSession'];
        }
        $conexion = new Conexion2();
        if (isset($this->empresa)) {
            $conexion->setEmpresa($this->empresa);
        }
        $conexion->Conectar();
        $DatosQuery = "";
        if(isset($_SESSION['idEmpresa']) && $_SESSION['idEmpresa'] != ""){
            $nombreBD = $conexion->getMYSQL_DB();
            if($nombreBD != ""){
                $tabla = $this->tabla;//esto ya lo debo saber del query
                $consultaObjeto = $this->obtenerLista("SELECT LOWER(TABLE_COMMENT) AS comentario FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = '$nombreBD' AND table_name = '$tabla'");
                $nombreObj = "";
                while($rs = mysql_fetch_array($consultaObjeto)){
                    $nombreObj = $rs['comentario'];
                }
                $arrayCampos = array();
                $idTabla = "";
                $consultaCampos = $this->obtenerLista("SELECT COLUMN_NAME, LOWER(COLUMN_COMMENT) AS COLUMN_COMMENT, COLUMN_KEY FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$tabla' AND table_schema = '$nombreBD'");
                while($rs = mysql_fetch_array($consultaCampos)){
                    if((strpos($rs['COLUMN_COMMENT'], "&&&")) !== false){//Si el comentario no viene con &&&, entonces lo ignoramos.
                        $comentario = explode("&&&", $rs['COLUMN_COMMENT']);
                        if($comentario[0] != ""){
                            $arrayCampos[$rs['COLUMN_NAME']] = $comentario[0];
                        }
                    }                    
                    if(strtoupper($rs['COLUMN_KEY']) == "PRI"){
                        $idTabla = $rs['COLUMN_NAME'];
                    }
                }
                //Si se identificó que había una llave principal de la tabla, buscamos ese ID, otro caso, buscamos el último registro guardado
                if($idTabla != ""){
                    if(strpos(strtoupper($query), "DELETE") === false){//Cuando se crea o edita
                        $whereEspecial = "";
                        if($where != ""){
                            $whereEspecial = $where;
                        }else{
                            $whereEspecial = "$idTabla = $id";
                        }
                        $result = $this->obtenerLista("SELECT * FROM $tabla WHERE $whereEspecial");
//                        if(mysql_num_rows($result) > 0){
//                            $DatosQuery = "Se $this->accion un(a) $nombreObj con el/los siguiente(s) dato(s): ";
//                        }
                        while($rs = mysql_fetch_array($result)){
                            foreach($arrayCampos AS $nombreCampo => $campo){
                                if($rs[$nombreCampo] != ""){
                                    $DatosQuery .= "<b>$campo = </b> " . $rs[$nombreCampo] . ",";
                                }
                            }
                        }
                    }else if($datosAnteriores != ""){//Al eliminar y tenemos el dato del estado anterior
                        $camposValor = explode(",", $datosAnteriores);
                        for($x = 0; $x < count($camposValor); $x++){
                            $campoSeparado = explode("=",$camposValor[$x]);
                            $campo = trim($campoSeparado[0]);
                            $valor = trim($campoSeparado[1]);
                            if(isset($arrayCampos[$campo]) && $arrayCampos[$campo] != "" && $valor != ""){
                                $DatosQuery .= "<b>" . $arrayCampos[$campo] . " = </b>$valor, ";
                            }
                        }
                        
                    }
                    if($DatosQuery != ""){
                        $DatosQuery = "Se $this->accion un(a) $nombreObj con el/los siguiente(s) dato(s): " . trim($DatosQuery,", ");
                    }
                }
            }
        }
//        $conexionMBD->Desconectar();
        //
        $consulta1 = "INSERT INTO c_log(IdQuery, Consulta, Fecha, IdUsuario, Tipo, Sesion, DatosQuery) VALUES(0,'" . str_replace("'", "´", 
        $query) . "',NOW(),$usuario,'$this->tipo','$sesion', '" . trim($DatosQuery,",") . "');";
        $conexion->Conectar();
        $conexion->Ejecutar($consulta1);
        $idLog = mysql_insert_id();
        $conexion->Desconectar();
        return $idLog;
    }
    
    private function preparaLog($consulta){
        $this->log = false;
        if (strpos(strtoupper($consulta), 'INSERT') !== false) {
            $this->tipo = "INSERT";
            $nombreTabla = explode(" ",substr($consulta, 12));
            $posicion = strpos($nombreTabla[0],"(");
            if($posicion !== false){    
                $this->tabla = substr($nombreTabla[0], 0, $posicion);
            }else{
                $this->tabla = $nombreTabla[0];
            }            
            $this->accion = "insertó";
            $this->log = true;
        } else if (strpos(strtoupper($consulta), 'DELETE') !== false) {
            $this->tipo = "DELETE FROM ";
            $nombreTabla = explode(" ",substr($consulta, 12));
            $posicion = strpos($nombreTabla[0],"(");
            if($posicion !== false){    
                $this->tabla = substr($nombreTabla[0], 0, $posicion);
            }else{
                $this->tabla = $nombreTabla[0];
            } 
            $this->accion = "eliminó";
            $this->log = true;
        } else if (strpos(strtoupper($consulta), 'UPDATE') !== false) {
            $this->tipo = "UPDATE";
            $nombreTabla = explode(" ",substr($consulta, 7));
            $posicion = strpos($nombreTabla[0],"(");
            if($posicion !== false){    
                $this->tabla = substr($nombreTabla[0], 0, $posicion);
            }else{
                $this->tabla = $nombreTabla[0];
            } 
            $this->accion = "actualizó";
            $this->log = true;
        }
    }

    public function getListaAlta($tabla, $order_by) {
        $conexion = new Conexion2();
        if (isset($this->empresa)) {
            $conexion->setEmpresa($this->empresa);
        }
        $conexion->Conectar();
        $order = "";
        if ($order_by != "") {
            $order = "ORDER BY $order_by ";
        }

        $consulta = "SELECT * FROM `" . $tabla . "` Where Activo = 1 " . $order . ";";
        if(isset($_SESSION['idUsuario']) && $tabla == "c_cliente"){            
            $usuario = new Usuario2();
            if ($usuario->getRegistroById($_SESSION['idUsuario']) && $usuario->getVendedor() == "1" && $usuario->getPuesto() != "1" && $usuario->getSupervisor() == "0") {
                $consulta = "SELECT * FROM `" . $tabla . "` Where EjecutivoCuenta = " . $_SESSION['idUsuario'] ." AND Activo = 1 " . $order . ";";                                
            }
            $conexion->Conectar();
        }              
        $query = $conexion->Ejecutar($consulta);
        $conexion->Desconectar();
        return $query;
    }


    public function getListaAlta2($tabla, $order_by, $idDocSup) {
        $conexion = new Conexion2();
        if (isset($this->empresa)) {
            $conexion->setEmpresa($this->empresa);
        }
        $conexion->Conectar();
        $order = "";
        if ($order_by != "") {
            $order = "ORDER BY $order_by ";
        }

        $consultaA2 = "SELECT * FROM `" . $tabla . "` Where Activo = 1 AND IdDocumentoSuperior=".$idDocSup." " . $order . ";";
        echo $consultaA2;
        if(isset($_SESSION['idUsuario']) && $tabla == "c_cliente"){            
            $usuario = new Usuario2();
            if ($usuario->getRegistroById($_SESSION['idUsuario']) && $usuario->getVendedor() == "1" && $usuario->getPuesto() != "1" && $usuario->getSupervisor() == "0") {
                $consultaA2 = "SELECT * FROM `" . $tabla . "` Where EjecutivoCuenta = " . $_SESSION['idUsuario'] ." AND Activo = 1 " . $order . ";";                                
            }
            $conexion->Conectar();
        }              
        $query = $conexion->Ejecutar($consultaA2);
        $conexion->Desconectar();
        return $query;
    }

    public function getListaAltaTodo($tabla, $order_by) {
        $conexion = new Conexion2();
        if (isset($this->empresa)) {
            $conexion->setEmpresa($this->empresa);
        }
        $conexion->Conectar();
        $order = "";
        if ($order_by != "") {
            $order = "ORDER BY `" . $order_by . "`";
        }
        $consulta = "SELECT * FROM `" . $tabla . "` " . $order . ";";
        if(isset($_SESSION['idUsuario']) && $tabla == "c_cliente"){            
            $usuario = new Usuario2();
            if ($usuario->getRegistroById($_SESSION['idUsuario']) && $usuario->getVendedor() == "1" && $usuario->getPuesto() != "1" && $usuario->getSupervisor() == "0") {
                $consulta = "SELECT * FROM `" . $tabla . "` Where EjecutivoCuenta = " . $_SESSION['idUsuario'] ." " . $order . ";";                                
            }
            $conexion->Conectar();
        }
        $query = $conexion->Ejecutar($consulta);
        $conexion->Desconectar();
        return $query;
    }

    function enviarCorreo($subject, $correos, $message, $pintar_mensaje) {
        $mail = new Mail();
        $parametroGlobal = new ParametroGlobal();
        if (isset($this->empresa)) {
            $parametroGlobal->setEmpresa($this->empresa);
        }
        if ($parametroGlobal->getRegistroById("10")) {
            $mail->setFrom($parametroGlobal->getValor());
        } else {
            //$mail->setFrom("notificador@administro.com.mx");
            $mail->setFrom("1715110005@utec-tgo.edu.mx");
        }
        $mail->setSubject($subject);
        $mail->setBody($message);
        foreach ($correos as $value) {
            if (isset($value) && $value != "" && filter_var($value, FILTER_VALIDATE_EMAIL)) {/* Si el correo es valido */
                $mail->setTo($value);
                if ($mail->enviarMail() == "1") {
                    if ($pintar_mensaje) {
                        echo "<br/>Un correo fue enviado a $value.";
                    }
                } else {
                    if ($pintar_mensaje) {
                        echo "<br/>Error: No se pudo enviar el correo a $value";
                    }
                }
            }
        }
    }

    function formatoFechaReportes($fecha) {
        if(empty($fecha)){
            return "";
        }
        $mes = "";
        $aux = explode("-", $fecha);
        switch ($aux[1]) {
            case '01':
                $mes = "Enero";
                break;
            case '02':
                $mes = "Febrero";
                break;
            case '03':
                $mes = "Marzo";
                break;
            case '04':
                $mes = "Abril";
                break;
            case '05':
                $mes = "Mayo";
                break;
            case '06':
                $mes = "Junio";
                break;
            case '07':
                $mes = "Julio";
                break;
            case '08':
                $mes = "Agosto";
                break;
            case '09':
                $mes = "Septiembre";
                break;
            case '10':
                $mes = "Octubre";
                break;
            case '11':
                $mes = "Noviembre";
                break;
            case '12':
                $mes = "Diciembre";
                break;
        }
        $formatFecha = $aux[2] . " de " . $mes . " de " . $aux[0];
        return $formatFecha;
    }

    function generaPass($longitud) {
        //Se define una cadena de caractares. Te recomiendo que uses esta.
        $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        //Obtenemos la longitud de la cadena de caracteres
        $longitudCadena = strlen($cadena);

        //Se define la variable que va a contener la contraseña
        $pass = "";
        //Se define la longitud de la contraseña, en mi caso 10, pero puedes poner la longitud que quieras
        $longitudPass = $longitud;

        //Creamos la contraseña
        for ($i = 1; $i <= $longitudPass; $i++) {
            //Definimos numero aleatorio entre 0 y la longitud de la cadena de caracteres-1
            $pos = rand(0, $longitudCadena - 1);

            //Vamos formando la contraseña en cada iteraccion del bucle, añadiendo a la cadena $pass la letra correspondiente a la posicion $pos en la cadena de caracteres definida.
            $pass .= substr($cadena, $pos, 1);
        }
        return $pass;
    }

    function satinizar_input($data) {
        $data = str_replace("'", "´", $data);
        $data = str_replace("\"", "´", $data);
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);        
        return $data;
    }
    
    /* backup the db OR just a table */
    function backup_tables() {        
        $tables = '*';       
        $return = "SET FOREIGN_KEY_CHECKS=0;\n\n\n";

        $catalogo = new Catalogo2();
        if (isset($this->empresa)) {
            $catalogo->setEmpresa($this->empresa);
        }
        
        //get all of the tables
        if ($tables == '*') {
            $tables = array();
            $result = $catalogo->obtenerLista('SHOW TABLES');
            while ($row = mysql_fetch_row($result)) {                
                $tables[] = $row[0];
            }
        } else {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }

        //cycle through
        foreach ($tables as $table) {
            $result = $catalogo->obtenerLista('SELECT * FROM ' . $table);
            $num_fields = mysql_num_fields($result);

            $return.= 'DROP TABLE ' . $table . ';';
            $row2 = mysql_fetch_row($catalogo->obtenerLista('SHOW CREATE TABLE ' . $table));
            $return.= "\n\n" . $row2[1] . ";\n\n";

            for ($i = 0; $i < $num_fields; $i++) {
                while ($row = mysql_fetch_row($result)) {
                    $return.= 'INSERT INTO ' . $table . ' VALUES(';
                    for ($j = 0; $j < $num_fields; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = ereg_replace("\n", "\\n", $row[$j]);
                        if (isset($row[$j])) {
                            $return.= '"' . $row[$j] . '"';
                        } else {
                            $return.= '""';
                        }
                        if ($j < ($num_fields - 1)) {
                            $return.= ',';
                        }
                    }
                    $return.= ");\n";
                }
            }
            $return.="\n\n\n";
        }
        $return .= "SET FOREIGN_KEY_CHECKS=1;\n";

        //save file       
        $this->nombre_respaldo = 'db-backup-' . time() . '-' . (md5(implode(',', $tables))) . '.sql';
        /* $handle = fopen("../".$this->nombre_respaldo, 'w+');
          if(!fwrite($handle, $return)){
          return false;
          }

          if(!fclose($handle)){
          return false;
          }else{
          return true;
          } */
        //Download file
        $sql = ereg_replace("CHARSET=utf-8", "CHARSET=utf-8;", $return);
        header("Content-disposition: attachment;filename=$this->nombre_respaldo");
        header("Content-Type: text/plain");
        echo $sql;
    }
    
    public function ejecutaConsultaActualizacionRegresandoFilas($consulta, $tabla, $where) {
        $estado = $this->obtenerEstadoAnterior($tabla, $where);
        $estado2 = $this->obtieneDatosAnteriores($tabla, $where);
        $conexion = new Conexion2();
        if (isset($this->empresa)) {
            $conexion->setEmpresa($this->empresa);
        }
        
        $conexion->Conectar();
        $query = $conexion->EjecutarRegresandoFilasAfectadas($consulta);  
        if($query != -1){
            /* Guardamos los queries que se ejecutan (INSERT, DELETE y UPDATE) */
            $this->preparaLog($consulta);
            if ($this->log && $query != 0) {//Si se va a registrar un log y hubo actualización
                if (isset($_SESSION['idUsuario'])) {
                    $usuario = $_SESSION['idUsuario'];
                } else {
                    /* Obtenemos el usuario que se pone por default segun los parametros globales */
                    $parametro = new ParametroGlobal();
                    if (isset($this->empresa)) {
                        $parametro->setEmpresa($this->empresa);
                    }
                    if ($parametro->getRegistroById("7")) {
                        $usuario = $parametro->getValor();
                    } else {
                        $usuario = "1";
                    }
                    $conexion->Conectar(); //Como parametros cierra la conexion, se tiene que volver a abrir
                }
                $sesion = "";
                if(isset($_SESSION['IdSession'])){
                    $sesion = $_SESSION['IdSession'];
                }
//                $consulta = "INSERT INTO c_log(IdQuery, Consulta, Fecha, IdUsuario, Tipo, Sesion) VALUES(0,'" . str_replace("'", "´", $consulta) . "',NOW(),$usuario,'$this->tipo','$sesion');";
//                $conexion->Ejecutar($consulta);
//                $idLog = mysql_insert_id();
                $whereExploded = explode("=", $where);
                $aux = "";
                if(isset($whereExploded[1])){
                       $aux = trim($whereExploded[1]," ");
                }                
                $idLog = $this->insertaRegistroLog($consulta, $usuario, $where, $aux,$estado);
                $conexion->Conectar();
                $conexion->Ejecutar("INSERT INTO c_loganterior(IdEstadoAnterior, IdQuery, EstadoAnterior,DatosAnteriores) VALUES(0,$idLog,'$estado','$estado2');");
            }            
        }
        $conexion->Desconectar();
        return $query;
    }

    public function getEmpresa() {
        return $this->empresa;
    }

    public function setEmpresa($empresa) {
        $this->empresa = $empresa;
    }

    public function rrmdir($ruta){
        if (is_dir($ruta)) { 
            $objects = scandir($ruta); 
            foreach ($objects as $object) { 
                if ($object != "." && $object != "..") { 
                    if (is_dir($ruta."/".$object))
                        $this->rrmdir($ruta."/".$object);
                    else
                        unlink($ruta."/".$object); 
                } 
            }
            rmdir($ruta); 
        }else{
            unlink($ruta); 
        } 
    }
    
}

?>