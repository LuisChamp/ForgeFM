<?php 

// Definición de la clase Conexion
class Conexion {

    // Propiedad para almacenar la conexión a la base de datos
    public $con;

    // Constructor de la clase
    public function __construct(){
        $this->con = new mysqli("localhost","gestoradmin","A1b2c3d4.","bd_forgeFM");
    }

    // Método para desconectar la base de datos
    public function desconectar(){
        // Verifica si la conexión existe
        if($this->con){
            // Cierra la conexión
            $this->con->close();
        }
    }
}

?>