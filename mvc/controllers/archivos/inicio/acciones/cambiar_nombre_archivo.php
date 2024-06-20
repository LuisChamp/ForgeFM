<?php 
// Se requieren los archivos de los modelos Archivo, Usuario y NotificacionArchivo
require_once "../../../../models/archivo.php";
require_once "../../../../models/usuario.php";
require_once "../../../../models/notificacionArchivo.php";

// Comienza la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Comprueba si se han enviado el ID de la ruta y el nuevo nombre del archivo
if(isset($_POST["idRuta"]) && isset($_POST["nombre"])){
    // Obtiene el ID de la ruta y el nuevo nombre del archivo desde la solicitud
    $idRuta = $_POST["idRuta"];
    $nombre = $_POST["nombre"];

    // Instancia objetos de las clases Archivo, Usuario y NotificacionArchivo
    $archivo = new Archivo();
    $usuario = new Usuario();
    $notificacionArchivo = new NotificacionArchivo();

    // Obtiene la ruta del archivo utilizando su ID
    $ruta = $archivo->obtenerRutaArchivo($idRuta);

    // Obtiene la ruta completa del archivo
    $rutaArchivo = $ruta[0]["ruta_archivo"];

    // Obtiene el nombre del archivo actual
    $nombreArchivo = basename($rutaArchivo);

    // Obtiene el ID del usuario actual
    $id_usuario = $usuario->obtenerIdUsuario($_SESSION["usuario"]);

    // Obtiene las partes de la ruta del archivo
    $partesRuta = explode("/", $rutaArchivo);

    // Elimina el nombre del archivo actual de las partes de la ruta
    array_pop($partesRuta);

    // Une las partes nuevamente para formar la nueva ruta con el nuevo nombre
    $rutaAVerificar = implode("/", $partesRuta)."/".$nombre;

    // Validar que el nombre no sea muy largo
    if(strlen($nombre) > 255) {
        $estructuraNotificacion = $notificacionArchivo->notificarErrorNombreLargo($id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }
    
    // Verifica si el nuevo nombre contiene una barra inclinada, lo que no está permitido
    if (!(strpos($nombre, '/') === false)) {
        $estructuraNotificacion = $notificacionArchivo->notificarErrorCambioArchivoNombre($nombreArchivo,$id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Verifica si el nuevo nombre contiene una barra invertida, lo que no está permitido
    if (!(strpos($nombre, '\\') === false)) {
        $estructuraNotificacion = $notificacionArchivo->notificarErrorCambioArchivoNombre2($nombreArchivo,$id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Verifica si el nuevo nombre contiene una comilla simple, lo que no está permitido
    if (!(strpos($nombre, "'") === false)) {
        $estructuraNotificacion = $notificacionArchivo->notificarErrorCambioArchivoNombre3($id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Verifica si ya existe un archivo con el nuevo nombre en la misma ubicación
    $validarRuta = $archivo->verificarNombreArchivo($rutaAVerificar);

    if($validarRuta){
        $estructuraNotificacion = $notificacionArchivo->notificarErrorCambiarNombreArchivoExistente($nombre,$id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Obtiene el ID del archivo desde el mapeo de la ruta
    $idArchivo = $archivo->obtenerIdDesdeMap($idRuta)[0]["id_archivo"];

    // Actualiza el nombre del archivo en la base de datos
    $actualizarNombreArchivo = $archivo->actualizarNombreArchivo($idArchivo,$nombre,$rutaAVerificar);

    if(!$actualizarNombreArchivo){
        $estructuraNotificacion = $notificacionArchivo->notificarErrorCambiarNombreArchivoBD($nombreArchivo,$id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Cambia el nombre del archivo en el sistema de archivos
    $actualizarNombreArchivoSistema = shell_exec('./../../../../../scripts/cambiarNombre/cambiarNombreArchivo.sh '."'$rutaArchivo' '$nombre'");

    if($actualizarNombreArchivoSistema  != "0"){
        switch ($actualizarNombreArchivoSistema) {
            case "No se han pasado los parametros necesarios":
                $estructuraNotificacion = $notificacionArchivo->notificarErrorCambiarNombreArchivoServidor($nombreArchivo,$id_usuario);
                break;
            case "Archivo no existe":
                $estructuraNotificacion = $notificacionArchivo->notificarErrorCambiarNombreArchivoServidor2($nombreArchivo,$id_usuario);
                break;
            case "Ya existe un archivo con ese nombre":
                $estructuraNotificacion = $notificacionArchivo->notificarErrorCambiarNombreArchivoServidor3($nombreArchivo,$id_usuario);
                break;
            case "Error al cambiar nombre de archivo":
                $estructuraNotificacion = $notificacionArchivo->notificarErrorCambiarNombreArchivoServidor4($nombreArchivo,$id_usuario);
                break;
            default:
                $estructuraNotificacion = $notificacionArchivo->notificarErrorCambiarNombreArchivoServidor4($nombreArchivo,$id_usuario);
                break;
        }
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Notifica el cambio exitoso del nombre del archivo
    $estructuraNotificacion = $notificacionArchivo->notificarCambiarNombreArchivo($nombreArchivo,$nombre,$id_usuario);

    echo json_encode(['respuesta' => 'exito','notificacion' => $estructuraNotificacion, 'tipo' => 'archivo'], JSON_UNESCAPED_UNICODE);
    exit();
}

?>
