<?php 
// Comienza la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirecciona a la página principal si el usuario no está autenticado
if (!isset($_SESSION["usuario"]) || empty($_SESSION["usuario"])) {
    header("Location: ../../");
    exit();
} 
?>
