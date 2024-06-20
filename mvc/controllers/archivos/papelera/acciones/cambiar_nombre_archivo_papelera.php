<?php 
// Incluir los archivos necesarios para el funcionamiento del script
require_once "../../../../models/archivo.php";
require_once "../../../../models/usuario.php";
require_once "../../../../models/notificacionArchivo.php";

// Iniciar sesión si no hay una sesión existente
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si se han enviado los datos necesarios para cambiar el nombre del archivo en la papelera
if(isset($_POST["idRuta"]) && isset($_POST["nombre"]) && isset($_POST["idRutaPapelera"])){

    // Obtener los datos enviados
    $idRuta = $_POST["idRuta"];
    $nombre = $_POST["nombre"];
    $idRutaPapelera = $_POST["idRutaPapelera"];

    // Crear instancias de las clases necesarias
    $archivo = new Archivo();
    $usuario = new Usuario();
    $notificacionArchivo = new NotificacionArchivo();

    // Obtener la ruta del archivo original
    $rutaArchivo = $archivo->obtenerRutaArchivo($idRuta)[0]["ruta_archivo"];

    // Obtener el nombre del archivo
    $nombreArchivo = basename($rutaArchivo);

    // Obtener el ID de usuario actual
    $id_usuario = $usuario->obtenerIdUsuario($_SESSION["usuario"]);

    // Validar que el nombre no sea muy largo
    if(strlen($nombre) > 255) {
        $estructuraNotificacion = $notificacionArchivo->notificarErrorNombreLargo($id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Verificar si el nuevo nombre contiene una barra inclinada ("/"), lo cual no se permite
    if (!(strpos($nombre, '/') === false)) {
        // Notificar del error al usuario
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
    
    // Obtener la ruta del archivo en la papelera
    $rutaArchivoPapelera = $archivo->obtenerRutaArchivoPapelera($idRutaPapelera)[0]["ruta_archivo_papelera"];

    // Dividir las rutas en partes para manipulación
    $partesRuta = explode("/", $rutaArchivo);
    $partesRutaPapelera = explode("/", $rutaArchivoPapelera);

    // Eliminar el último elemento del array (nombre del archivo)
    array_pop($partesRuta);
    array_pop($partesRutaPapelera);

    // Unir las partes de la ruta nuevamente para formar la nueva ruta
    $nuevaRuta = implode("/", $partesRuta)."/".$nombre;
    $nuevaRutaPapelera = implode("/", $partesRutaPapelera)."/".$idRutaPapelera."_".$nombre;

    // Obtener el ID del archivo y el ID de la papelera
    $idArchivo = $archivo->obtenerIdDesdeMap($idRuta)[0]["id_archivo"];
    $idPapelera = $archivo->obtenerIdArchivoPapelera2($idArchivo);

    // Actualizar el nombre del archivo en la papelera en la base de datos
    $actualizarNombreArchivoPapelera = $archivo->actualizarNombreArchivoPapelera($idArchivo,$nombre,$nuevaRuta,$nuevaRutaPapelera);

    // Verificar si la actualización en la base de datos fue exitosa
    if(!$actualizarNombreArchivoPapelera){
        // Notificar error si la actualización en la base de datos falló
        $estructuraNotificacion = $notificacionArchivo->notificarErrorCambiarNombreArchivoPapeleraBD($nombreArchivo,$id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Ejecutar el script de cambio de nombre del archivo en la papelera en el sistema
    $actualizarNombreArchivoPapeleraSistema = shell_exec('./../../../../../scripts/cambiarNombre/cambiarNombreArchivoPapelera.sh '."'$rutaArchivoPapelera' '$nombre' '$idPapelera'");

    // Verificar si la ejecución del script en el sistema fue exitosa
    if($actualizarNombreArchivoPapeleraSistema != "0"){
        // Notificar error si la ejecución del script en el sistema falló
        switch ($actualizarNombreArchivoPapeleraSistema) {
            case "No se han pasado los parametros necesarios":
                $estructuraNotificacion = $notificacionArchivo->notificarErrorCambiarNombreArchivoPapeleraServidor($nombreArchivo,$id_usuario);
                break;
            case "Archivo no existe":
                $estructuraNotificacion = $notificacionArchivo->notificarErrorCambiarNombreArchivoPapeleraServidor2($nombreArchivo,$id_usuario);
                break;
            case "Directorio no existe":
                $estructuraNotificacion = $notificacionArchivo->notificarErrorCambiarNombreArchivoPapeleraServidor3($nombreArchivo,$id_usuario);
                break;
            case "Error al mover archivo en la carpeta":
                $estructuraNotificacion = $notificacionArchivo->notificarErrorCambiarNombreArchivoPapeleraServidor4($nombreArchivo,$id_usuario);
                break;
            default:
                $estructuraNotificacion = $notificacionArchivo->notificarErrorCambiarNombreArchivoPapeleraServidor4($nombreArchivo,$id_usuario);
                break;
        }
        // Enviar una respuesta JSON con el error notificado
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Notificar éxito en el cambio de nombre del archivo en la papelera
    $estructuraNotificacion = $notificacionArchivo->notificarCambiarNombreArchivoPapelera($nombreArchivo,$nombre,$id_usuario);

    // Enviar una respuesta JSON con la notificación de éxito
    echo json_encode(['respuesta' => 'exito','notificacion' => $estructuraNotificacion, 'tipo' => 'archivo'], JSON_UNESCAPED_UNICODE);
    exit();

}

?>
