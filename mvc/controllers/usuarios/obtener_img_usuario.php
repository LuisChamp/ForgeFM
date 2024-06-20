<?php 
// Incluir el modelo Usuario
require_once "../../models/usuario.php";

// Iniciar una nueva sesión si no hay una sesión existente
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Crear una instancia de la clase Usuario
$usuario = new Usuario();

// Obtener el nombre de usuario de la sesión actual
$nombreUsuario = $_SESSION["usuario"];

// Obtener la ruta de la imagen del usuario desde la base de datos
$obtenerImgUsuario = $usuario->obtenerImgUsuario($nombreUsuario)[0]["ruta_imagen"];

// Convertir la respuesta a formato JSON y enviarla
echo json_encode(["resultado" => "Exitoso", "rutaImagen" => "$obtenerImgUsuario"]);

?>
