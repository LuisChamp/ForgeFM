<?php 
// Incluir el modelo necesario para el controlador
require_once "../../models/carpeta.php";

// Iniciar la sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si la variable "idRuta" ha sido enviada
if(isset($_POST["idRuta"])){
    // Obtener el ID de la ruta
    $idRuta = $_POST["idRuta"];

    // Crear una instancia del modelo Carpeta
    $carpeta = new Carpeta();

    // Obtener la ruta de la carpeta a partir de su ID
    $rutaCarpeta = $carpeta->obtenerRutaCarpeta($idRuta)[0]["ruta_carpeta"];

    // Obtener el nombre de la carpeta a partir de su ruta
    $nombreCarpeta = basename($rutaCarpeta);

    // Convertir el nombre de la carpeta a formato JSON y enviarlo como respuesta
    echo json_encode($nombreCarpeta, JSON_UNESCAPED_UNICODE);
    exit();
}

?>
