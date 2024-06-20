<?php 
// Incluir los modelos necesarios
require_once "../../../models/usuario.php";
require_once "../../../models/notificacionUsuario.php";

// Verificar si el parámetro "sesion" ha sido enviado
if(isset($_GET["sesion"])){
    // Iniciar una nueva sesión si no hay una sesión existente
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Verificar si el parámetro "eliminar" ha sido enviado
    if(isset($_GET["eliminar"])){
        // Limpiar todas las variables de sesión
        session_unset(); 
        // Destruir la sesión actual
        session_destroy();
        // Redirigir al usuario a la página principal
        header("Location: ../../../../");
        exit();
    }

    // Crear una instancia de la clase Usuario
    $usuario = new Usuario();

    // Crear una instancia de la clase NotificacionUsuario
    $notificacionUsuario = new NotificacionUsuario();

    // Obtener el ID del usuario actual a partir de la sesión
    $id_usuario = $usuario->obtenerIdUsuario($_SESSION["usuario"]);
    
    // Notificar el cierre de sesión del usuario
    $notificacionUsuario->notificarCerrarSesion($id_usuario);

    // Limpiar todas las variables de sesión
    session_unset(); 
    // Destruir la sesión actual
    session_destroy();

    // Redirigir al usuario a la página principal
    header("Location: ../../../../");
    exit();
}
?>
