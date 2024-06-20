<?php 
// Verificar si no hay una sesión iniciada y, si es así, iniciar una nueva sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Crear un array con las rutas de la carpeta y la papelera, obtenidas de las variables de sesión
$arrayRutas = [$_SESSION["ruta_carpeta"], $_SESSION["ruta_papelera"]];

// Convertir el array a formato JSON y enviarlo
echo json_encode($arrayRutas);

?>
