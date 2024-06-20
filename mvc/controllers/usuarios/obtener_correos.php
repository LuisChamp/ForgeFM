<?php 
// Incluir el modelo Usuario
require_once "../../models/usuario.php";

// Iniciar una nueva sesión si no hay una sesión existente
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Crear una instancia de la clase Usuario
$usuario = new Usuario();

// Obtener una lista de todos los correos electrónicos de los usuarios
$arrayCorreos = $usuario->obtenerCorreos();

// Extraer solo los correos electrónicos del array de resultados
$correos = array_column($arrayCorreos, 'correo');

// Convertir la respuesta a formato JSON y enviarla
echo json_encode(["respuesta" => "Exitoso", "correos" => $correos]);

?>
