<?php

include_once("Catalogo2.class.php");

class Session {

    private $id_usu;
    private $usuario;
    private $password;
    private $id_empresa;
    private $nombre_empresa;
    private $empresa;
    
    private $IntentosFallidosLoggin;
    private $Activo;
    private $arrayEmpresa = array();
    private $arrayActivo = array();
    private $arrayNumeroIntentos = array();

    function getLogin($usuario, $password) {
        $consulta = ("SELECT IdUsuario FROM `c_usuario` WHERE Password='" . $password . "' AND NombreUsuario='" . $usuario . "' AND Activo = 1;");
        //echo $consulta;
        $catalogo = new Catalogo2();
        if (isset($this->empresa)) {
            $catalogo->setEmpresa($this->empresa);
        }        
        $query = $catalogo->obtenerLista($consulta);
        while ($rs = mysql_fetch_array($query)) {
            $this->id_usu = $rs['IdUsuario'];
            $this->usuario = $usuario;
            $this->password = $password;
            return $this->id_usu;
        }
        return "";
    }

    function getLogginMultiBD($usuario, $password) {
        $this->conn = new ConexionMultiBD();
        $consulta = "SELECT u.id_usuario, u.id_empresa, e.nombre_empresa, u.Activo, u.IntentosFallidosLoggin FROM `c_usuariomultibd` AS u 
            LEFT JOIN c_empresa AS e ON e.id_empresa = u.id_empresa 
            WHERE e.Activo = 1 AND u.Password='" . $password . "' AND u.Loggin='" . $usuario . "' AND u.Activo = 1;";        
        $query = mysql_query($consulta);
        while ($rs = mysql_fetch_array($query)) {
            $this->id_usu = $rs['id_usuario'];
            $this->id_empresa = $rs['id_empresa'];
            $this->nombre_empresa = $rs['nombre_empresa'];
            $this->Activo = $rs['Activo'];
            $this->IntentosFallidosLoggin = $rs['IntentosFallidosLoggin'];
            $this->usuario = $usuario;
            $this->password = $password;
            $this->conn->Desconectar();
            return true;
        }
        $this->conn->Desconectar();
        return false;
    }
    
    public function desencriptarContrasena($password){  
        //Se toman solo los caracteres que forman el real password
        $password_real = substr($password, 0, 8);
        $password_real .= substr($password, 13, 10);
        $password_real .= substr($password, 28, 8);
        $password_real .= substr($password, 41, strlen($password)-1);
        return $password_real;
    }
    
    /**
     * Regresa una clave unica activa para el usuario actual
     * @param type $sizeClave tamanio de la clave     
     * @return type
     */
    public function generarClaveSession($sizeClave) {
        $clave = "";
        $clave_existe = false;
        $respuesta = array();
        $catalogo = new Catalogo2();
        if (isset($this->empresa)) {
            $catalogo->setEmpresa($this->empresa);
        }

        $parametros = new Parametros();
        if (isset($this->empresa)) {
            $parametros->setEmpresa($this->empresa);
        }
        
        //Obtenemos el maximo de minutos que una session puede estar activa
        $max_minute = 240; //Valor por default
        if ($parametros->getRegistroById(6)) {//Valor configurado en la bd
            $max_minute = (int) $parametros->getValor();
        }
                
        
        $consulta = "SELECT IdSession, ClaveSession, FechaCreacion FROM c_session 
                WHERE IdUsuario = $this->id_usu AND Activo = 1 AND TIMESTAMPDIFF(MINUTE,FechaCreacion,NOW()) < $max_minute 
                ORDER BY IdSession LIMIT 0,1;";
        $result = $catalogo->obtenerLista($consulta);
        if (mysql_num_rows($result) > 0) {//Si el usuario actual ya tiene una clave activa, se regresa esa clave
            while ($rs = mysql_fetch_array($result)) {
                //$respuesta['FechaCreacion'] = $rs['FechaCreacion'];
                $respuesta['IdSession'] = $rs['ClaveSession'];
                //$respuesta['DuracionMinutos'] = $max_minute;
            }
            return $respuesta;
        } else {//Si el usuario no tiene clave activa se genera una nueva                         
            //Desactivamos cualquier Session activa
            $consulta = "UPDATE c_session SET Activo = 0 WHERE IdUsuario = $this->id_usu;";
            $catalogo->obtenerLista($consulta);
            do {//Se repite el proceso hasta que se encuentra una clave valida
                $clave = $this->generarClavealeatoria($sizeClave); //Generamos la clave aleatoria    
                //verificamos que la clave no existe en la multi-base
                $conn = new ConexionMultiBD();
                $result = $conn->Ejecutar("SELECT id_usuario FROM `c_usuariomultibd` WHERE IdSession = '$clave';");
                $conn->Desconectar();
                if(mysql_num_rows($result) > 0){
                    $clave_existe = true;
                }else{
                    $consulta = "SELECT IdSession FROM c_session WHERE ClaveSession = '$clave' AND Activo = 1;";
                    $result = $catalogo->obtenerLista($consulta);
                    if (mysql_num_rows($result) > 0) { //Si la clave ya existes y está vigente, se tiene que volver a generar otra
                        $clave_existe = true;
                    } else {//Si la clave no existe, la insertamos en la BD y la devolvemos como resultado del método
                        $conn = new ConexionMultiBD();
                        $consulta = "UPDATE `c_usuariomultibd` SET IdSession = '$clave',FechaModificacion=NOW() WHERE Loggin = '$this->usuario' AND `Password` = '$this->password';";
                        $result = $conn->Ejecutar($consulta);
                        
                        $conn->Desconectar();
                        
                        $hoy = getdate();
                        //$fechaCreacion = $hoy['year']."-".$hoy['mon']."-".$hoy['mday']." ".$hoy['hours'].":".$hoy['minutes'].":".$hoy['seconds'];
                        $consulta = "INSERT INTO c_session(IdSession, ClaveSession, IdUsuario, Activo, FechaCreacion) VALUES(0,'$clave',$this->id_usu,1,NOW());";
                        $idSession = $catalogo->insertarRegistro($consulta);
                        if ($idSession != NULL && $idSession != 0) {
                            $clave_existe = false;
                            //$respuesta['FechaCreacion'] = $fechaCreacion;
                            $respuesta['IdSession'] = $clave;
                            //$respuesta['DuracionMinutos'] = $max_minute;
                        }
                    }
                }                
            } while ($clave_existe);
        }
        return $respuesta;
    }
    
    /**
     * Genera una clave alfanumerica de tamanio "n"
     * @param type $sizeClave tamanio de la clave
     * @return type
     */
    function generarClavealeatoria($sizeClave) {
        //Se define una cadena de caractares. Te recomiendo que uses esta.
        $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890?[]=*-+.";
        //Obtenemos la longitud de la cadena de caracteres
        $longitudCadena = strlen($cadena);

        //Se define la variable que va a contener la contraseña
        $pass = "";
        //Se define la longitud de la contraseña, en mi caso 10, pero puedes poner la longitud que quieras        
        $longitudPass = $sizeClave;

        //Creamos la contraseña
        for ($i = 1; $i <= $longitudPass; $i++) {
            //Definimos numero aleatorio entre 0 y la longitud de la cadena de caracteres-1
            $pos = rand(0, $longitudCadena - 1);

            //Vamos formando la contraseña en cada iteraccion del bucle, añadiendo a la cadena $pass la letra correspondiente a la posicion $pos en la cadena de caracteres definida.
            $pass .= substr($cadena, $pos, 1);
        }
        return $pass;
    }
    
    public function logginWithSession($clave) {
        $consulta = "SELECT IdSession, FechaCreacion, IdUsuario FROM c_session WHERE ClaveSession = '$clave' AND Activo = 1;";        
        $catalogo = new Catalogo2();
        if (isset($this->empresa)) {
            $catalogo->setEmpresa($this->empresa);
        }
        $result = $catalogo->obtenerLista($consulta);            
        if (mysql_num_rows($result) > 0) {//Si existe una session activa para esa clave            
            $parametros = new Parametros();
            if (isset($this->empresa)) {
                $parametros->setEmpresa($this->empresa);
            }
            //Obtenemos el maximo de minutos que una session puede estar activa
            $max_minute = 240; //Valor por default
            if ($parametros->getRegistroById(6)) {//Valor configurado en la bd
                $max_minute = (int) $parametros->getValor();
            }

            while ($rs = mysql_fetch_array($result)) {//Recorremos la session activa
                $consulta = "SELECT TIMESTAMPDIFF(MINUTE,'" . $rs['FechaCreacion'] . "',NOW()) AS Time;";
                $resultTime = $catalogo->obtenerLista($consulta);
                while ($rs2 = mysql_fetch_array($resultTime)) {
                    $tiempo_transcurrido = (int) $rs2['Time'];
                    if ($tiempo_transcurrido > $max_minute) {//Si la session ha estado activa mas del tiempo permitido
                        $consulta = "UPDATE c_session SET Activo = 0 WHERE IdUsuario = " . $rs['IdUsuario'] . ";";
                        $catalogo->obtenerLista($consulta);
                        return -2;
                    } else {//Si la session sigue activa correctamente
                        return $rs['IdUsuario'];
                    }
                }
                return -3;
            }
        } else {//Esta clave ya no esta activa
            return -1;
        }
    }

    
    function revalidarSesion($IdSession){
        $consulta = "SELECT s.IdUsuario, u.`Password` AS pass, u.Loggin
FROM `c_session` AS s 
LEFT JOIN c_usuario AS u ON u.IdUsuario = s.IdUsuario
WHERE ClaveSession = '$IdSession' AND s.Activo = 1;";
        $catalogo = new Catalogo2();
        if (isset($this->empresa)) {
            $catalogo->setEmpresa($this->empresa);
        }
        $result = $catalogo->obtenerLista($consulta);
        if(mysql_num_rows($result) > 0){            
            while($rs = mysql_fetch_array($result)){                
                $this->id_usu = $rs['IdUsuario']; 
                $this->usuario = $rs['Loggin'];
                $this->password = $rs['pass'];
                $respuesta = $this->generarClaveSession(32,$IdSession);
                $respuesta['IdUsuario'] = $this->getId_usu();                
                return $respuesta;
            }
        }else{
            return -1;
        }
    }
    
    public function obtenerEmpresaBySesion($IdSession){
        $this->conn = new ConexionMultiBD();
        $result = mysql_query("SELECT id_empresa FROM `c_usuariomultibd` WHERE IdSession = '$IdSession';");
        $this->conn->Desconectar();
        if(mysql_num_rows($result) > 0){
            while($rs = mysql_fetch_array($result)){
                return $rs['id_empresa'];
            }
        }else{
            return 0;
        }
    }
    
    public function actualizaNumeroIntentosFallidosByLoggin($loggin){
        $this->conn = new ConexionMultiBD();
        $result = mysql_query("UPDATE c_usuariomultibd SET IntentosFallidosLoggin = IntentosFallidosLoggin + 1 WHERE Loggin = '$loggin' AND id_empresa = "
                . "$this->id_empresa");
        $this->conn->Desconectar();
        if($result == 1){
            return true;
        }
        return false;
    }
    
    public function actualizaNumeroIntentosFallidos(){
        $this->conn = new ConexionMultiBD();
        $result = mysql_query("UPDATE c_usuariomultibd SET IntentosFallidosLoggin = $this->IntentosFallidosLoggin, Activo = $this->Activo WHERE "
                . "id_usuario = $this->id_usu");
        $this->conn->Desconectar();
        if($result == 1){
            return true;
        }
        return false;
    }
    
    public function getIdUsuarioByLoggin($loggin){
        $this->conn = new ConexionMultiBD();
        $consulta = "SELECT id_usuario, Activo, IntentosFallidosLoggin, id_empresa FROM c_usuariomultibd WHERE Loggin = '$loggin' AND Activo = 1;";
        $result = mysql_query($consulta);
        $this->conn->Desconectar();
        while($rs = mysql_fetch_array($result)){
            if(!in_array($rs['id_empresa'], $this->arrayEmpresa)){
                array_push($this->arrayEmpresa, $rs['id_empresa']);
            }
//            $this->id_usu = $rs['id_usuario'];
//            $this->Activo = $rs['Activo'];
//            $this->IntentosFallidosLoggin = $rs['IntentosFallidosLoggin'];
//            $this->id_empresa = $rs['id_empresa'];
//            $this->arrayEmpresa[$rs['id_usuario']] = $rs['id_empresa'];
//            $this->arrayActivo[$rs['id_usuario']] = $rs['Activo'];
//            $this->arrayNumeroIntentos[$rs['id_usuario']] = $rs['IntentosFallidosLoggin'];
        }
    }
    
    function InactivarUsuariosByLoggin($loggin, $intentosMaximos) {
        $consulta = "UPDATE c_usuariomultibd SET Activo = 0 WHERE Loggin = '$loggin' AND IntentosFallidosLoggin >= $intentosMaximos AND id_empresa = "
                . "$this->id_empresa";
        $this->conn = new ConexionMultiBD();
        $result = mysql_query($consulta);
        $numRowsAffected = mysql_affected_rows();
        $this->conn->Desconectar();
        
        return $numRowsAffected;
    }
    
    public function getId_usu() {
        return $this->id_usu;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getId_empresa() {
        return $this->id_empresa;
    }

    public function getNombre_empresa() {
        return $this->nombre_empresa;
    }

    public function setNombre_empresa($nombre_empresa) {
        $this->nombre_empresa = $nombre_empresa;
    }

    public function getEmpresa() {
        return $this->empresa;
    }

    public function setEmpresa($empresa) {
        $this->empresa = $empresa;
    }

    function getIntentosFallidosLoggin() {
        return $this->IntentosFallidosLoggin;
    }

    function setIntentosFallidosLoggin($IntentosFallidosLoggin) {
        $this->IntentosFallidosLoggin = $IntentosFallidosLoggin;
    }
    
    function getActivo() {
        return $this->Activo;
    }

    function setActivo($Activo) {
        $this->Activo = $Activo;
    }   
    
    function setId_usu($id_usu) {
        $this->id_usu = $id_usu;
    } 
    
    function getArrayEmpresa() {
        return $this->arrayEmpresa;
    }

    function getArrayActivo() {
        return $this->arrayActivo;
    }

    function getArrayNumeroIntentos() {
        return $this->arrayNumeroIntentos;
    }

    function setId_empresa($id_empresa) {
        $this->id_empresa = $id_empresa;
    }
}

