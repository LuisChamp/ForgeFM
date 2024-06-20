<?php 
// Incluir archivos de modelo necesarios para el controlador
require_once "../../../../models/carpeta.php";
require_once "../../../../models/usuario.php";
require_once "../../../../models/notificacionCarpeta.php";

// Iniciar una sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si las variables necesarias han sido enviadas
if(isset($_POST["idRuta"]) && isset($_POST["nombre"]) && isset($_POST["idRutaPapelera"])){

    // Asignar variables 
    $idRuta = $_POST["idRuta"];
    $nombre = $_POST["nombre"];
    $idRutaPapelera = $_POST["idRutaPapelera"];

    // Crear instancias de los modelos necesarios
    $carpeta = new Carpeta();
    $usuario = new Usuario();
    $notificacionCarpeta = new NotificacionCarpeta();

    // Obtener la ruta de la carpeta a partir del ID de la ruta
    $rutaCarpeta = $carpeta->obtenerRutaCarpeta($idRuta)[0]["ruta_carpeta"];

    // Obtener el nombre de la carpeta desde la ruta
    $nombreCarpeta = basename($rutaCarpeta);

    // Obtener el ID del usuario a partir de la sesión
    $id_usuario = $usuario->obtenerIdUsuario($_SESSION["usuario"]);

    // Validar que el nombre no sea muy largo
    if(strlen($nombre) > 255) {
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorNombreLargo($id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Verificar si el nuevo nombre de la carpeta contiene una barra inclinada '/'
    if (!(strpos($nombre, '/') === false)) {
        // Notificar error si el nombre contiene una barra inclinada
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorCambioCarpetaNombre($nombreCarpeta, $id_usuario);
        echo json_encode(['respuesta' => 'error', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Verifica si el nuevo nombre contiene una barra invertida, lo que no está permitido
    if (!(strpos($nombre, '\\') === false)) {
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorCambioCarpetaNombre2($nombreCarpeta,$id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Verifica si el nuevo nombre contiene una comilla simple, lo que no está permitido
    if (!(strpos($nombre, "'") === false)) {
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorCambioCarpetaNombre3($nombreCarpeta,$id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }
    
    // Obtener la ruta de la carpeta en la papelera a partir del ID de la ruta de la papelera
    $rutaCarpetaPapelera = $carpeta->obtenerRutaCarpetaPapelera($idRutaPapelera)[0]["ruta_carpeta_papelera"];

    // Dividir las rutas en partes para manipularlas
    $partesRuta = explode("/", $rutaCarpeta);
    $partesRutaPapelera = explode("/", $rutaCarpetaPapelera);

    // Eliminar el último componente de las rutas (nombre actual de la carpeta)
    array_pop($partesRuta);
    array_pop($partesRutaPapelera);

    // Formar las nuevas rutas uniendo las partes y añadiendo el nuevo nombre de la carpeta
    $nuevaRuta = implode("/", $partesRuta)."/".$nombre;
    $nuevaRutaPapelera = implode("/", $partesRutaPapelera)."/".$idRutaPapelera."_".$nombre;

    // Obtener el ID de la carpeta a partir del ID de la ruta
    $idCarpeta = $carpeta->obtenerIdDesdeMap($idRuta)[0]["id_carpeta"];

    // Obtener el ID de la carpeta en la papelera
    $idPapelera = $carpeta->obtenerIdCarpetaPapelera2($idCarpeta);

    // Actualizar el nombre de la carpeta en la papelera
    $actualizarNombreCarpetaPapelera = $carpeta->actualizarNombreCarpetaPapelera($idCarpeta, $nombre, $nuevaRuta, $nuevaRutaPapelera);

    if(!$actualizarNombreCarpetaPapelera){
        // Notificar error si no se pudo actualizar el nombre de la carpeta en la papelera en la BD
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorCambiarNombreCarpetaPapeleraBD($nombreCarpeta, $id_usuario);
        echo json_encode(['respuesta' => 'error', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Actualizar el nombre de las subcarpetas en la papelera
    $actualizarNombreCarpetaHijasPapelera = $carpeta->actualizarNombreCarpetaHijasPapelera($rutaCarpeta, $nuevaRuta, $rutaCarpetaPapelera, $nuevaRutaPapelera);

    if(!$actualizarNombreCarpetaHijasPapelera){
        // Notificar advertencia si no se pudieron actualizar los nombres de las subcarpetas en la papelera
        $estructuraNotificacion = $notificacionCarpeta->notificarAdvCambioNombreSubcarpetasPapelera($nombreCarpeta, $id_usuario);
        echo json_encode(['respuesta' => 'advertencia', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Segunda actualización de los nombres de las subcarpetas en la papelera
    $actualizarNombreCarpetaHijasPapelera2 = $carpeta->actualizarNombreCarpetaHijasPapelera2($rutaCarpeta, $nuevaRuta, $rutaCarpetaPapelera, $nuevaRutaPapelera);

    if(!$actualizarNombreCarpetaHijasPapelera2){
        // Notificar advertencia si no se pudieron actualizar los nombres de las subcarpetas en la papelera
        $estructuraNotificacion = $notificacionCarpeta->notificarAdvCambioNombreSubcarpetasPapelera($nombreCarpeta, $id_usuario);
        echo json_encode(['respuesta' => 'advertencia', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Actualizar el nombre de los archivos en la papelera
    $actualizarNombreArchivosHijosPapelera = $carpeta->actualizarNombreArchivosHijosPapelera($rutaCarpeta, $nuevaRuta, $rutaCarpetaPapelera, $nuevaRutaPapelera);

    if(!$actualizarNombreArchivosHijosPapelera){
        // Notificar advertencia si no se pudieron actualizar los nombres de los archivos en la papelera
        $estructuraNotificacion = $notificacionCarpeta->notificarAdvCambioNombreArchivosPapelera($nombreCarpeta, $id_usuario);
        echo json_encode(['respuesta' => 'advertencia', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();   
    }

    // Segunda actualización de los nombres de los archivos en la papelera
    $actualizarNombreArchivosHijosPapelera2 = $carpeta->actualizarNombreArchivosHijosPapelera2($rutaCarpeta, $nuevaRuta, $rutaCarpetaPapelera, $nuevaRutaPapelera);

    if(!$actualizarNombreArchivosHijosPapelera2){
        // Notificar advertencia si no se pudieron actualizar los nombres de los archivos en la papelera
        $estructuraNotificacion = $notificacionCarpeta->notificarAdvCambioNombreArchivosPapelera($nombreCarpeta, $id_usuario);
        echo json_encode(['respuesta' => 'advertencia', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit(); 
    }

    // Ejecutar un script del sistema para actualizar el nombre de la carpeta en la papelera
    $actualizarNombreCarpetaPapeleraSistema = shell_exec('./../../../../../scripts/cambiarNombre/cambiarNombreCarpetaPapelera.sh '."'$rutaCarpetaPapelera' '$nombre' '$idPapelera'");

    if($actualizarNombreCarpetaPapeleraSistema  != "0"){
        // Notificar diferentes errores dependiendo del mensaje devuelto por el script del sistema
        switch ($actualizarNombreCarpetaPapeleraSistema) {
            case "No se proporcionaron los parametros necesarios":
                $estructuraNotificacion = $notificacionCarpeta->notificarErrorCambiarNombreCarpetaPapeleraServidor($nombreCarpeta, $id_usuario);
                break;
            case "Carpeta no existe":
                $estructuraNotificacion = $notificacionCarpeta->notificarErrorCambiarNombreCarpetaPapeleraServidor3($nombreCarpeta, $id_usuario);
                break;
            case "Carpeta con ese nombre ya existe":
                $estructuraNotificacion = $notificacionCarpeta->notificarErrorCambiarNombreCarpetaPapeleraServidor2($nombreCarpeta, $id_usuario);
                break;
            case "Error al cambiar nombre de carpeta":
                $estructuraNotificacion = $notificacionCarpeta->notificarErrorMoverCarpetaServidor($nombreCarpeta, $id_usuario);
                break;
            default:
                $estructuraNotificacion = $notificacionCarpeta->notificarErrorCambiarNombreCarpetaPapeleraServidor4($nombreCarpeta, $id_usuario);
                break;
        }
        echo json_encode(['respuesta' => 'error', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Notificar éxito en la actualización del nombre de la carpeta en la papelera
    $estructuraNotificacion = $notificacionCarpeta->notificarCambioNombreCarpetaPapelera($nombreCarpeta, $nombre, $id_usuario);
    echo json_encode(['respuesta' => 'exito', 'notificacion' => $estructuraNotificacion, 'tipo' => 'carpeta'], JSON_UNESCAPED_UNICODE);
    exit();
}
?>
