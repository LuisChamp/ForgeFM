<?php 
// Incluir el modelo Usuario
require_once "../../models/usuario.php";

// Iniciar una nueva sesión si no hay una sesión existente
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Crear una instancia de la clase Usuario
$usuario = new Usuario();

// Obtener el nombre de usuario de la sesión actual desde la variable de sesión
$nombreUsuario = $_SESSION["usuario"];

// Obtener el nombre completo del usuario desde la base de datos
$obtenerNombreUsuario = $usuario->obtenerNombreCompleto($nombreUsuario);

// Convertir la respuesta a formato JSON y enviarla
echo json_encode(["resultado" => "Exitoso", "nombreCompleto" => "$obtenerNombreUsuario"]);

?>
