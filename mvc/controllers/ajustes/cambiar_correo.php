<?php 
// Se requieren los archivos de modelo para Usuario y NotificacionUsuario
require_once "../../models/usuario.php";
require_once "../../models/notificacionUsuario.php";

// Comienza la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si se ha enviado el formulario para cambiar el correo electrónico
if(isset($_POST["nuevoCorreo"])){

    // Obtiene el nuevo correo electrónico del formulario
    $nuevoCorreo = $_POST["nuevoCorreo"];

    // Instancia un objeto de la clase Usuario
    $usuario = new Usuario();

    // Instancia un objeto de la clase NotificacionUsuario
    $notificacionUsuario = new NotificacionUsuario();

    // Obtiene el ID del usuario actual
    $id_usuario = $usuario->obtenerIdUsuario($_SESSION["usuario"]);

    // Verifica si el ID del usuario es válido
    if(!$id_usuario){
        // Notifica un error si el usuario es inválido
        $estructuraNotificacion = $notificacionUsuario->notificarErrorCambiarCorreoUsuarioInvalido($id_usuario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Obtiene el correo electrónico anterior del usuario
    $antiguoCorreo = $usuario->obtenerCorreo($id_usuario)[0]["correo"];

    // Verifica si el nuevo correo electrónico es el mismo que el antiguo
    if($antiguoCorreo === $nuevoCorreo){
        // Notifica un error si el nuevo correo es igual al antiguo
        $estructuraNotificacion = $notificacionUsuario->notificarErrorMismoCorreo($id_usuario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Verifica si el nuevo correo electrónico tiene el formato correcto
    $patronEmail = $usuario->patronEmail($nuevoCorreo);
    if(!$patronEmail){
        // Notifica un error si el formato del correo electrónico es incorrecto
        $estructuraNotificacion = $notificacionUsuario->notificarErrorCambiarCorreoIncorrecto($id_usuario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Verifica si el nuevo correo electrónico ya está en uso por otro usuario
    $validarEmail = $usuario->validarEmail($nuevoCorreo);
    if($validarEmail){
        // Notifica un error si el nuevo correo ya está en uso
        $estructuraNotificacion = $notificacionUsuario->notificarErrorCambiarCorreoInexistente($nuevoCorreo,$id_usuario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Actualiza el correo electrónico del usuario en la base de datos
    $actualizarCorreo = $usuario->actualizarCorreo($antiguoCorreo,$nuevoCorreo,$id_usuario);
    if(!$actualizarCorreo){
        // Notifica un error si no se pudo actualizar el correo electrónico en la base de datos
        $estructuraNotificacion = $notificacionUsuario->notificarErrorCambiarCorreoBD($id_usuario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Notifica que el cambio de correo electrónico fue exitoso
    $estructuraNotificacion = $notificacionUsuario->notificarCambiarCorreo($antiguoCorreo,$nuevoCorreo,$id_usuario);
    echo json_encode(['respuesta' => 'exito','notificacion' => $estructuraNotificacion, 'correo' => $nuevoCorreo], JSON_UNESCAPED_UNICODE);
    exit();
}

?>
