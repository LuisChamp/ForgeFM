<?php 
// Incluye los archivos necesarios para las clases Carpeta, Usuario y NotificacionCarpeta
require_once "../../../../models/carpeta.php";
require_once "../../../../models/usuario.php";
require_once "../../../../models/notificacionCarpeta.php";

// Inicia la sesión si aún no se ha iniciado
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si se han enviado los datos
if(isset($_POST["idRuta"]) && isset($_POST["nombre"])){
    // Obtiene los datos enviados
    $idRuta = $_POST["idRuta"];
    $nombre = $_POST["nombre"];

    // Crea instancias de las clases Carpeta, Usuario y NotificacionCarpeta
    $carpeta = new Carpeta();
    $usuario = new Usuario();
    $notificacionCarpeta = new NotificacionCarpeta();

    // Obtiene la ruta de la carpeta con el ID proporcionado
    $ruta = $carpeta->obtenerRutaCarpeta($idRuta);

    // Extrae la ruta de la carpeta
    $rutaCarpeta = $ruta[0]["ruta_carpeta"];

    // Divide la ruta en partes separadas por "/"
    $partesRuta = explode("/", $rutaCarpeta);

    // Elimina el último elemento del arreglo, que corresponde al nombre de la carpeta actual
    array_pop($partesRuta);

    // Une las partes de la ruta nuevamente para formar la nueva ruta con el nuevo nombre de carpeta
    $rutaAVerificar = implode("/", $partesRuta)."/".$nombre;

    // Verifica si ya existe una carpeta con el mismo nombre en la misma ruta
    $validarRuta = $carpeta->verificarNombreCarpeta($rutaAVerificar);

    // Obtiene el ID del usuario actualmente autenticado
    $id_usuario = $usuario->obtenerIdUsuario($_SESSION["usuario"]);

    // Obtiene el nombre de la carpeta a partir de su ruta completa
    $nombreCarpeta = basename($rutaCarpeta);

    // Validar que el nombre no sea muy largo
    if(strlen($nombre) > 255) {
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorNombreLargo($id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Verifica si el nombre de la carpeta contiene el carácter "/"
    if (!(strpos($nombre, '/') === false)) {
        // Notifica un error si el nombre de la carpeta contiene "/"
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorCambioCarpetaNombre($nombreCarpeta,$id_usuario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
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

    // Verifica si ya existe una carpeta con el mismo nombre en la misma ruta
    if($validarRuta){
        // Notifica un error si ya existe una carpeta con el mismo nombre en la misma ruta
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorCarpetaExistente2($nombreCarpeta,$id_usuario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    } 
    
    // Obtiene el ID de la carpeta a partir del mapeo
    $idCarpeta = $carpeta->obtenerIdDesdeMap($idRuta)[0]["id_carpeta"];

    // Actualiza el nombre de la carpeta en la base de datos
    $actualizarNombreCarpeta = $carpeta->actualizarNombreCarpeta($idCarpeta,$nombre,$rutaAVerificar);

    // Verifica si se pudo actualizar el nombre de la carpeta en la base de datos
    if(!$actualizarNombreCarpeta){
        // Notifica un error si no se pudo actualizar el nombre de la carpeta en la base de datos
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorCambioCarpetaBD($nombreCarpeta,$id_usuario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Actualiza el nombre de las subcarpetas de la carpeta actual
    $actualizarNombreCarpetaHijas = $carpeta->actualizarNombreCarpetaHijas($rutaCarpeta,$rutaAVerificar);

    // Verifica si se pudieron actualizar los nombres de las subcarpetas de la carpeta actual
    if(!$actualizarNombreCarpetaHijas){
        // Notifica una advertencia si no se pudieron actualizar los nombres de las subcarpetas
        $estructuraNotificacion = $notificacionCarpeta->notificarAdvCambioNombreSubcarpetas($nombreCarpeta,$id_usuario);
        echo json_encode(['respuesta' => 'advertencia','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Actualiza el nombre de los archivos hijos de la carpeta actual
    $actualizarNombreArchivosHijos = $carpeta->actualizarNombreArchivosHijos($rutaCarpeta,$rutaAVerificar);
        
    // Verifica si se pudieron actualizar los nombres de los archivos hijos de la carpeta actual
    if(!$actualizarNombreArchivosHijos){
        // Notifica una advertencia si no se pudieron actualizar los nombres de los archivos hijos
        $estructuraNotificacion = $notificacionCarpeta->notificarAdvCambioNombreArchivos($nombreCarpeta,$id_usuario);
        echo json_encode(['respuesta' => 'advertencia','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Ejecuta un script de shell para cambiar el nombre de la carpeta en el sistema de archivos
    $actualizarNombreCarpetaSistema = shell_exec('./../../../../../scripts/cambiarNombre/cambiarNombreCarpeta.sh '."'$rutaCarpeta' '$nombre'");

    // Verifica si hubo algún error al ejecutar el script de cambio de nombre de carpeta en el servidor
    if($actualizarNombreCarpetaSistema  != "0"){
        // Notifica un error específico dependiendo del resultado de la ejecución del script
        switch ($actualizarNombreCarpetaSistema) {
            case "Carpeta no existe":
                $estructuraNotificacion = $notificacionCarpeta->notificarErrorMoverCarpetaServidor($nombreCarpeta,$id_usuario);
                break;
            case "Carpeta con nuevo nombre ya existe":
                $estructuraNotificacion = $notificacionCarpeta->notificarErrorCambioCarpetaServidor2($nombreCarpeta,$id_usuario);
                break;
            case "Error al cambiar nombre de carpeta":
                $estructuraNotificacion = $notificacionCarpeta->notificarErrorCambioCarpetaServidor3($nombreCarpeta,$id_usuario);
                break;
                default:
                // Notifica un error genérico si no se puede determinar la causa específica del error
                $estructuraNotificacion = $notificacionCarpeta->notificarErrorCambioCarpetaServidor($nombreCarpeta,$id_usuario);
                break;
                }
            // Imprime el mensaje de error correspondiente y finaliza el script
            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }
    
    // Notifica el éxito del cambio de nombre de la carpeta
    $estructuraNotificacion = $notificacionCarpeta->notificarCambioNombreCarpeta($nombreCarpeta,$nombre,$id_usuario);
    echo json_encode(['respuesta' => 'exito','notificacion' => $estructuraNotificacion, 'tipo' => 'carpeta'], JSON_UNESCAPED_UNICODE);
    exit();
}
?>