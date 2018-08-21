<?php

class Conexion2 {
    var $db;   
    var $empresa;
    private $MYSQL_HOST;
    private $MYSQL_DB;
    private $MYSQL_LOGIN;
    private $MYSQL_PASS;
    
    public function Conectar() {   
        
        $this->MYSQL_HOST = "localhost";
        $this->MYSQL_DB = "pec";
        $this->MYSQL_LOGIN = "root";
        $this->MYSQL_PASS = "1234";
		
        /*$this->MYSQL_HOST = "50.22.25.101";
        $this->MYSQL_DB = "clasificador";
        $this->MYSQL_LOGIN = "clasificador";
        $this->MYSQL_PASS = "Di1XNm5JI0tKLJQv";
        $hostname = "localhost";
		$nombreUsuario = "root";
		$pass = "1234";
		$nombreConexion = mysqli_connect($hostname , $nombreUsuario , $pass);
		mysqli_select_db($nombreConexion, "pec");*/

        //echo "$this->MYSQL_HOST, $this->MYSQL_DB, $this->MYSQL_LOGIN, $this->MYSQL_PASS";
        $this->db = @mysql_connect($this->MYSQL_HOST, $this->MYSQL_LOGIN, $this->MYSQL_PASS);        
        $return = @mysql_query("SET NAMES 'utf8'", $this->db); 
        $return = @mysql_query("SET time_zone = '-06:00';", $this->db); 
        if (!$this->db) {
            echo ('<b> Lo sentimos, tuvimos un problema :(, se ha presentado el error 102 del sistema, vuelva a intentarlo más tarde.</b>');
            exit;
        }
        
        if(!@mysql_select_db($this->MYSQL_DB)){
            echo "<br/>Error: no se pudo conectar a la BD, revisa los datos de conexion.";
            exit;
        }
    }

    function Desconectar() {
        if (gettype($this->db) == "resource") {
            mysql_close($this->db);
        }
    }

    function Ejecutar($query) {
        $resultado = mysql_query($query);        
        if (!$resultado) {
            $resultado = mysql_error();
        }        
        return $resultado;
    }
    
    function EjecutarRegresandoFilasAfectadas($query){
        mysql_query($query);
//        if (!$resultado) {
//            $resultado = mysql_error();
//        }  
        return mysql_affected_rows();
    }
     
    public function getMYSQL_HOST() {
        return $this->MYSQL_HOST;
    }

    public function getMYSQL_DB() {
        return $this->MYSQL_DB;
    }

    public function getMYSQL_LOGIN() {
        return $this->MYSQL_LOGIN;
    }

    public function getMYSQL_PASS() {
        return $this->MYSQL_PASS;
    }
}

?>