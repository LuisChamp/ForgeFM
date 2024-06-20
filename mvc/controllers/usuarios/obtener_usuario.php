<?php 
// Verificar si no hay una sesión iniciada y, si es así, iniciar una nueva sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Enviar el nombre del usuario de la sesión actual
echo json_encode($_SESSION["usuario"]);

?>
