<?php 
// Comienza la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirecciona a la página de inicio si el usuario está autenticado y tiene una ruta de carpeta definida
if (isset($_SESSION["usuario"]) && !empty($_SESSION["usuario"]) && isset($_SESSION["ruta_carpeta"])) {
    header("Location: inicio");
    exit();
} 
?>
