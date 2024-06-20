<?php 

// Incluir conexión a la base de datos
require_once "conexion.php";

// Definición de la clase Usuario
class Usuario {

    // Método privado para ejecutar consultas SQL
    private function obtenerDatos($sql){
        // Se instancia un objeto de la clase Conexion para establecer la conexión a la base de datos
        $conexion = new Conexion();
        $con = $conexion->con;

        $resultado = [];
        
        // Se ejecuta la consulta SQL
        $consulta = $con->query($sql);
        $i = 0;

        if (!$consulta) {
            // Si hay un error en la consulta, lanzamos una excepción
            throw new Exception("Error en la consulta: " . $con->error);
        }

        // Almacenar los resultados de la consulta en un array asociativo
        while ($fila = $consulta->fetch_assoc()) {
            $resultado[$i] = $fila;
            $i++;
        }

        return $resultado;
    }

    // Método privado para ejecutar consultas y retornar un booleano
    private function ejecutarConsulta($sql) {
         // Se instancia un objeto de la clase Conexion para establecer la conexión a la base de datos
        $conexion = new Conexion();
        $con = $conexion->con;
        $consulta = $con->query($sql);

        // Si hay un error en la consulta, retornamos false, de lo contrario, retornamos true
        if (!$consulta) {
            return false;
        } else {
            return true;
        }
    }

    // Método para obtener el ID de usuario a partir del nombre
    public function obtenerIdUsuario($nombre){
        $consulta = $this->obtenerDatos("SELECT id_usuario FROM usuarios WHERE nombre = '$nombre';");
    
        if(count($consulta) !== 0){
            $id_usuario = $consulta["0"]["id_usuario"];
        } else {
            $id_usuario = false;
        }

        return $id_usuario;
    }

    // Método para verificar si un nombre de usuario ya existe en la base de datos
    public function validarUsuario($nombre){
        $consulta = $this->obtenerDatos("SELECT nombre FROM usuarios WHERE nombre = '$nombre';");

        if(count($consulta) > 0){
            return true;
        } else {
            return false;
        }
    }

    // Método para obtener el HASH de un usuario
    public function obtenerPass($nombre){
        $consulta = $this->obtenerDatos("SELECT contrasenia FROM usuarios WHERE nombre = '$nombre';");
    
        if(count($consulta) !== 0){
            $hash_pass = $consulta["0"]["contrasenia"];
        } else {
            $hash_pass = false;
        }

        return $hash_pass;
    }

    // Método para verificar el HASH de un usuario
    public function verificarHash($pass,$hash_pass){
        if (password_verify($pass, $hash_pass)) {
            return true;
        } else {
            return false;
        }
    }

    // Método para verificar si el correo ya ha sido registrado
    public function validarCorreo($correo){
        $consulta = $this->obtenerDatos("SELECT correo FROM usuarios WHERE correo = '$correo';");

        if(count($consulta) > 0){
            return true;
        } else {
            return false;
        }
    }

    // Método para verificar si el correo cumple con el formato
    public function patronEmail($correo){

        $patron = "/^[a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,15})$/";

        if(preg_match($patron,$correo)){
            return true;
        } else{
            return false;
        }
    }

    // Método para obtener el HASH
    public function obtenerHash($pass){
        $hashContrasena = password_hash($pass, PASSWORD_DEFAULT);

        return $hashContrasena;
    }

    // Método para obtener el ultimo id posterior de usuario
    public function ultimoIdUsuario(){
        $consulta = $this->obtenerDatos("SELECT id_usuario FROM usuarios ORDER BY id_usuario DESC LIMIT 1;");
    
        if(count($consulta) === 0){
            $id_posterior = 1;
        } else {
            $id_posterior = $consulta["0"]["id_usuario"] + 1;
        }
        return $id_posterior;
    }

    // Método para insertar un nuevo usuario
    public function agregarUsuario($idPosterior,$correo,$nombre,$apellido,$hashContrasena,$fecha,$almacenamientoTotal,$rutaImagenDefault){
        return $this->ejecutarConsulta("INSERT INTO usuarios VALUES ($idPosterior,'$correo','$nombre','$apellido','$hashContrasena','$fecha',$almacenamientoTotal,'$rutaImagenDefault');");
    }

    // Método para obtener datos de un usuario
    public function obtenerDatosUsuario($nombre){
        return $this->obtenerDatos("SELECT nombre, apellido, correo FROM usuarios where nombre = '$nombre'");
    }

    // Método para obtener datos de un usuario propietario de un elemento
    public function obtenerDatosUsuarioCompartir($nombre){
        return $this->obtenerDatos("SELECT nombre, apellido, correo, ruta_imagen FROM usuarios where nombre = '$nombre'");
    }

    // Método para validar si un usuario es propietario de un elemento
    public function validarNombrePropietario($nombrePropietario){
        $consulta = $this->obtenerDatos("SELECT nombre FROM usuarios WHERE nombre = '$nombrePropietario'");

        if(count($consulta) === 0){
            return false;
        } else {
            return true;
        }
    }

    // Método para obtener el almacenamiento total de un usuario
    public function obtenerAlmacenamientoTotal($nombreUsuario){
        return $this->obtenerDatos("SELECT almacenamiento_total FROM usuarios where nombre = '$nombreUsuario'");
    }

    // Método para validar si el correo existe o no en la base de datos
    public function validarEmail($correo){
        $consulta = $this->obtenerDatos("SELECT correo FROM usuarios WHERE correo = '$correo'");

        if(count($consulta) === 0){
            return false;
        } else {
            return true;
        }
    }

    // Método para obtener el id de un usuario en base a su correo
    public function obtenerIdUsuarioE($correo){
        return $this->obtenerDatos("SELECT id_usuario FROM usuarios where correo = '$correo'");
    }

    // Método para obtener el nombre de un usuario
    public function obtenerNombreUsuario($idUsuario){
        return $this->obtenerDatos("SELECT nombre FROM usuarios where id_usuario = $idUsuario");
    }

    // Método para obtener el correo de un usuario
    public function obtenerCorreo($idUsuario){
        return $this->obtenerDatos("SELECT correo FROM usuarios where id_usuario = $idUsuario");
    }

    // Método para actualizar el correo de un usuario
    public function actualizarCorreo($antiguoCorreo,$nuevoCorreo,$idUsuario){
        return $this->ejecutarConsulta("UPDATE usuarios SET correo = '$nuevoCorreo' WHERE correo = '$antiguoCorreo' AND id_usuario = $idUsuario");
    }

    // Método para actualizar contraseña de un usuario
    public function actualizarPass($usuario,$nuevoHash){
        return $this->ejecutarConsulta("UPDATE usuarios SET contrasenia = '$nuevoHash' WHERE nombre  = '$usuario'");
    }
    
    // Método para eliminar cuenta de un usuario
    public function eliminarCuenta($idUsuario){
        return $this->ejecutarConsulta("DELETE FROM usuarios WHERE id_usuario = $idUsuario");
    }

    // Método para actualizar imagen de perfil de un usuario
    public function actualizarImgUsuario($nombreUsuario,$rutaImagen){
        return $this->ejecutarConsulta("UPDATE usuarios SET ruta_imagen = '$rutaImagen' WHERE nombre = '$nombreUsuario'");
    }

    // Método para obtener imagen de perfil de un usuario
    public function obtenerImgUsuario($nombreUsuario){
        return $this->obtenerDatos("SELECT ruta_imagen FROM usuarios WHERE nombre = '$nombreUsuario'");
    }

    // Método para obtener nombre completo de un usuario
    public function obtenerNombreCompleto($nombreUsuario){
        $consulta = $this->obtenerDatos("SELECT nombre, apellido FROM usuarios WHERE nombre = '$nombreUsuario'");

        $nombre = $consulta[0]["nombre"];
        $apellido = $consulta[0]["apellido"];

        return $nombre." ".$apellido;
    }

    // Método para obtener todos los correos que hay en la base de datos
    public function obtenerCorreos(){
        return $this->obtenerDatos("SELECT correo FROM usuarios");
    }
}

?>