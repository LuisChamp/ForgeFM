<?php 
// Incluir conexión a la base de datos
require_once "conexion.php";
// Definición de la clase Paginacion
class Notificacion {
    // Método privado para ejecutar consultas SQL
    public function obtenerDatos($sql){
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

        while ($fila = $consulta->fetch_assoc()) {
            $resultado[$i] = $fila;
            $i++;
        }

        return $resultado;
    }
    
    // Método privado para ejecutar consultas y retornar un booleano
    public function ejecutarConsulta($sql) {
         // Se instancia un objeto de la clase Conexion para establecer la conexión a la base de datos
        $conexion = new Conexion();
        $con = $conexion->con;

        $consulta = $con->query($sql);
        // Si hay un error en la consulta, retornamos false, de lo contrario, retornamos true
        if (!$consulta) {
            throw new Exception("Error en la consulta: " . $con->error);
        }

        return true;
    }
    
    // Método para generar una notificación de éxito
    public function notificacionExito($mensaje){
        return $estructura = "<div><svg xmlns='http://www.w3.org/2000/svg' height='30' width='30' viewBox='0 0 512 512'><path fill='#15d52c' d='M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z'/></svg></div><div><p>$mensaje</p><a href='registros' class='ver_detalles_registro'>Ver detalles</a></div>";

    }

    // Método para generar una notificación de error
    public function notificacionError($mensaje){
        return $estructura = "<div><svg xmlns='http://www.w3.org/2000/svg' height='30' width='30' viewBox='0 0 512 512'><path fill='#ff1f1f' d='M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z'/></svg></div><div><p>$mensaje</p><a href='registros' class='ver_detalles_registro'>Ver detalles</a></div>";
    }

    // Método para generar una notificación de información
    public function notificacionInfo($mensaje){
        return $estructura = "<div><svg xmlns='http://www.w3.org/2000/svg' height='30' width='30' viewBox='0 0 512 512'><path fill='#2f72e4' d='M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z'/></svg></div><div><p>$mensaje</p><a href='registros' class='ver_detalles_registro'>Ver detalles</a></div>";
    }

    // Método para generar una notificación de advertencia
    public function notificacionAdvertencia($mensaje){
        return $estructura = "<div><svg xmlns='http://www.w3.org/2000/svg' height='30' width='30' viewBox='0 0 512 512'><path fill='#ffd43b' d='M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z'/></svg></div><div><p>$mensaje</p><a href='registros' class='ver_detalles_registro'>Ver detalles</a></div>";
    }

    // Método para insertar un registro en la base de datos
    public function insertarRegistro($id_evento, $evento, $tipo, $id_usuario){
        $fecha_hora=date("Y-m-d H:i:s");

        return $this->ejecutarConsulta("INSERT INTO registros VALUES (NULL,'$id_evento','$fecha_hora','$evento','$tipo', $id_usuario)");
    }

    // Método para mostrar una notificación según el tipo
    public function mostrarNotificacion($tipo,$mensajeNotificacion) {
        $estructuraNotificacion = '';
        // Según el tipo de notificación, llamamos al método correspondiente para generar la estructura HTML
        switch ($tipo) {
            case 'ERROR':
                $estructuraNotificacion = $this->notificacionError($mensajeNotificacion);
                break;
            case 'EXITO':
                $estructuraNotificacion = $this->notificacionExito($mensajeNotificacion);
                break;
            case 'ADVERTENCIA':
                $estructuraNotificacion = $this->notificacionAdvertencia($mensajeNotificacion);
                break;
            case 'INFO':
                $estructuraNotificacion = $this->notificacionInfo($mensajeNotificacion);
                break;                                
            default:
                break;
        }

        return $estructuraNotificacion;
    }

    // Método para obtener registros de la base de datos para un usuario
    public function obtenerRegistros($idUsuario){
        return $this->obtenerDatos("SELECT id_evento, fecha_hora, evento, tipo FROM registros WHERE id_usuario = $idUsuario ORDER BY fecha_hora DESC");
    }

    // Método para crear la estructura HTML de un registro
    public function crearRegistro($idEvento,$fecha,$evento,$tipoEvento){
        $estructuraRegistro = "<li class='table-row evento'><div class='col col-1 nivel' data-label='Nivel'>$tipoEvento</div><div class='col col-2 id_evento' data-label='ID Evento'>$idEvento</div><div class='col col-3 fecha_evento' data-label='Fecha'>$fecha</div><div class='col col-4 texto_evento' data-label='Evento'>$evento</div></li>";

        return $estructuraRegistro;
    }

    // Método para obtener el total de registros para un usuario 
    public function obtenerTotalRegistros($idUsuario){
        $consulta = $this->obtenerDatos("SELECT count(*) as totalRegistros FROM registros WHERE id_usuario = $idUsuario");

        return $consulta[0]["totalRegistros"];
    }

    // Método para obtener registros paginados para un usuario
    public function obtenerRegistrosPaginacion($idUsuario,$numObj,$offset){
        return $this->obtenerDatos("SELECT id_evento, fecha_hora, evento, tipo FROM registros WHERE id_usuario = $idUsuario ORDER BY fecha_hora DESC LIMIT $numObj OFFSET $offset");
    }
}