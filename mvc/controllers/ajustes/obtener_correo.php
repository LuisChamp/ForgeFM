<?php 
// Se requiere el archivo del modelo para Usuario
require_once "../../models/usuario.php";

// Comienza la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Instancia un objeto de la clase Usuario
$usuario = new Usuario();

// Obtiene el ID del usuario actual
$idUsuario = $usuario->obtenerIdUsuario($_SESSION["usuario"]);

// Si no se puede obtener el ID del usuario, muestra un mensaje de error
if(!$idUsuario){
    echo json_encode("Error al validar usuario");
    exit();
}

// Obtiene el correo actual del usuario
$correoActual = $usuario->obtenerCorreo($idUsuario)[0]["correo"];

// Devuelve el correo actual del usuario como resultado exitoso en formato JSON
echo json_encode(["resultado" => "Exitoso", "correo" => "$correoActual"]);
exit();
?>
