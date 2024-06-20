<?php
// Incluir archivos necesarios
require_once "../../../../lib/libreria.php";
require_once "../../../models/usuario.php";
require_once "../../../models/notificacionUsuario.php";

// Iniciar una nueva sesión si no hay una sesión existente
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Función para mostrar un mensaje de error y redirigir al usuario a la página de inicio de sesión
function mostrarErrorYRedirigir($mensaje) {
    $_SESSION['error_usuario'] = "<h4><b>$mensaje</b></h4>";
    // Redirigir a la página de inicio de sesión 
    header("Location: ../../../../inicio_sesion"); 
    exit();
}

// Función para verificar si un usuario existe en la base de datos
function usuarioExiste($nombre){
    $usuarioObj = new Usuario();
    return $usuarioObj->validarUsuario($nombre); 
}

// Función para iniciar sesión del usuario
function iniciarSesionUsuario($nombre) {
    if (!isset($_SESSION["usuario"]) || empty($_SESSION["usuario"])) {
        // Si no hay sesión de usuario existente, iniciar una nueva sesión
        $_SESSION["usuario"] = $nombre;
        $_SESSION['correcto_inicio'] = "<h4><b>Inicio de sesión válido</b></h4>";
    } else {
        // Si ya hay una sesión de usuario, mostrar mensaje de sesión existente
        $_SESSION['inicio_existente'] = "<h4><b>Ya se ha iniciado sesión</b></h4>";
    }
    // Redirigir a la página de inicio de sesión
    header("Location: ../../../../inicio_sesion"); 
    exit();
}

// Verificar si el formulario de inicio de sesión ha sido enviado
if(!isset($_POST["enviar_inicio"])){
    // Incluir la página de inicio de sesión si no se ha enviado el formulario
    include "../../../../inicio_sesion"; 
    exit();
}

// Validar campos vacíos
if (validarCamposVacios($_REQUEST)) {
    mostrarErrorYRedirigir("Rellene todos los campos por favor");
}

// Verificar si el usuario existe en la base de datos
if(!usuarioExiste($_POST["form_inicio_usuario"])){
    // Mostrar mensaje de error si el usuario no existe
    mostrarErrorYRedirigir("Usuario no válido, regístrese por favor"); 
}

// Función para iniciar sesión
function iniciarSesion($nombre, $pass) {
    $usuarioObj = new Usuario(); 
    $notificacionUsuario = new NotificacionUsuario();

    // Obtener el ID del usuario
    $id_usuario = $usuarioObj->obtenerIdUsuario($nombre);

    // Obtener el hash de la contraseña
    $hash_pass = $usuarioObj->obtenerPass($nombre); 
    
    if ($usuarioObj->verificarHash($pass, $hash_pass)) {
        // Si la contraseña es correcta, notificar inicio de sesión y establecer la sesión del usuario
        $notificacionUsuario->notificarInicioSesion($id_usuario);
        iniciarSesionUsuario($nombre);
    } else {
        // Mostrar mensaje de error si la contraseña es incorrecta
        mostrarErrorYRedirigir("Contraseña ingresada incorrecta");
    }
}

// Iniciar sesión del usuario con las credenciales proporcionadas
iniciarSesion($_POST["form_inicio_usuario"], $_POST["form_inicio_pass"]);

?>
