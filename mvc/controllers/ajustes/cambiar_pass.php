<?php 
// Se requieren los archivos de modelo para Usuario y NotificacionUsuario
require_once "../../models/usuario.php";
require_once "../../models/notificacionUsuario.php";

// Comienza la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si se han enviado los datos del formulario para cambiar la contraseña
if(isset($_POST["passActual"]) && isset($_POST["passNueva"]) && isset($_POST["passNuevaConf"])){

    // Obtiene los datos del formulario
    $passActual = $_POST["passActual"];
    $passNueva = $_POST["passNueva"];
    $passNuevaConf = $_POST["passNuevaConf"];

    // Instancia un objeto de la clase Usuario
    $usuario = new Usuario();
    // Instancia un objeto de la clase NotificacionUsuario
    $notificacionUsuario = new NotificacionUsuario();

    // Obtiene el nombre de usuario de la sesión actual
    $nombreUsuario = $_SESSION["usuario"];

    // Obtiene el ID del usuario actual
    $id_usuario = $usuario->obtenerIdUsuario($nombreUsuario);

    // Obtiene la contraseña almacenada en la base de datos para el usuario actual
    $passUsuario = $usuario->obtenerPass($nombreUsuario);

    // Verifica si la contraseña actual proporcionada coincide con la almacenada en la base de datos
    $verificarPassActual = $usuario->verificarHash($passActual,$passUsuario);

    // Si la contraseña actual no coincide, notifica un error
    if(!$verificarPassActual){
        $estructuraNotificacion = $notificacionUsuario->notificarErrorCambiarPassIncorrecta($id_usuario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Verifica si las contraseñas nuevas ingresadas coinciden
    if($passNueva !== $passNuevaConf){
        $estructuraNotificacion = $notificacionUsuario->notificarErrorCambiarPassNoCoinciden($id_usuario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Verifica si la contraseña nueva es igual a la contraseña actual
    if($passActual === $passNueva){
        $estructuraNotificacion = $notificacionUsuario->notificarErrorCambiarPassIguales($id_usuario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Obtiene el hash de la nueva contraseña
    $nuevoHash = $usuario->obtenerHash($passNueva);

    // Actualiza la contraseña en la base de datos
    $actualizarPass = $usuario->actualizarPass($nombreUsuario,$nuevoHash);

    // Si no se puede actualizar la contraseña, notifica un error
    if(!$actualizarPass){
        $estructuraNotificacion = $notificacionUsuario->notificarErrorCambiarPassBD($id_usuario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Notifica que el cambio de contraseña fue exitoso
    $estructuraNotificacion = $notificacionUsuario->notificarCambiarPass($id_usuario);
    echo json_encode(['respuesta' => 'exito','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
    exit();
}

?>
