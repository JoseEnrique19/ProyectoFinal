<?php

include_once("Conexion2.class.php");
include_once("Alumnos.class.php");
/**
 * Description of Catalogo2
 *
 * @author DDHL
 */
class Catalogo2 {

    private $tabla;
    private $accion;


    public function obtenerLista($consulta) {
        $conexion = new Conexion2();
        $conexion->Conectar();
        $query = $conexion->Ejecutar($consulta);      
        $conexion->Desconectar();
        return $query;
    }

    public function ejecutaConsultaActualizacion($consulta, $tabla, $where) {
        $estado = $this->obtenerEstadoAnterior($tabla, $where);
        $estado2 = $this->obtieneDatosAnteriores($tabla, $where);
        $conexion = new Conexion2();
        $conexion->Conectar();
        $query = $conexion->Ejecutar($consulta);  
        $conexion->Desconectar();
        return $query;
    }
    
    private function obtenerEstadoAnterior($tabla, $where){        
        $conexion = new Conexion2();
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
        $conexion->Conectar();
        $query = $conexion->Ejecutar($consulta);
        $id = mysql_insert_id();
        $conexion->Desconectar();
        return $id;
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
    
}

?>