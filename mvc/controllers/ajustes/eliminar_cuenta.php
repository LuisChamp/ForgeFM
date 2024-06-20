<?php 
// Se requieren los archivos de modelo para Usuario y NotificacionUsuario
require_once "../../models/usuario.php";
require_once "../../models/notificacionUsuario.php";

// Comienza la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si se ha enviado la confirmación para eliminar la cuenta
if(isset($_POST["confirmacion"])){
    
    // Instancia un objeto de la clase Usuario
    $usuario = new Usuario();
    // Instancia un objeto de la clase NotificacionUsuario
    $notificacionUsuario = new NotificacionUsuario();

    // Obtiene el nombre de usuario de la sesión actual
    $nombreUsuario = $_SESSION["usuario"];

    // Obtiene el ID del usuario actual
    $id_usuario = $usuario->obtenerIdUsuario($nombreUsuario);

    // Si no se puede obtener el ID del usuario, notifica un error
    if(!$id_usuario){
        $estructuraNotificacion = $notificacionUsuario->notificarErrorEliminarCuentaUsuarioIncorrecto($id_usuario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    } 

    // Intenta eliminar la cuenta del usuario de la base de datos
    $eliminarCuenta = $usuario->eliminarCuenta($id_usuario); 

    // Si no se puede eliminar la cuenta de la base de datos, notifica un error
    if(!$eliminarCuenta){
        $estructuraNotificacion = $notificacionUsuario->notificarErrorEliminarCuentaBD($id_usuario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Intenta ejecutar un script para eliminar los datos del usuario del sistema
    $eliminarCuentaSistema = shell_exec('./../../../scripts/eliminar/eliminarUsuario.sh '."'$nombreUsuario'");

    // Si ocurre un error al ejecutar el script, notifica el error específico
    if($eliminarCuentaSistema != "0"){
        switch ($eliminarCuentaSistema) {
            case "No se ha proporcionado el parametro necesario":
                $estructuraNotificacion = $notificacionUsuario->notificarErrorEliminarCuentaSistema($id_usuario);
                break;
            case "Carpeta no existe":
                $estructuraNotificacion = $notificacionUsuario->notificarErrorEliminarCuentaSistema2($id_usuario);
                break;
            case "Error al eliminar carpeta de usuario":
                $estructuraNotificacion = $notificacionUsuario->notificarErrorEliminarCuentaSistema3($id_usuario);
                break;
            default:
                $estructuraNotificacion = $notificacionUsuario->notificarErrorEliminarCuentaSistema4($id_usuario);
                break;
        }
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Notifica que la cuenta ha sido eliminada exitosamente
    $estructuraNotificacion = $notificacionUsuario->notificarEliminarCuenta($id_usuario);
    echo json_encode(['respuesta' => 'exito','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
    exit();
}

?>
