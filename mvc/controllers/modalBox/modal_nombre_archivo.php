<?php 
// Incluir el modelo necesario para el controlador
require_once "../../models/archivo.php";

// Iniciar la sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si la variable "idRuta" ha sido enviada
if(isset($_POST["idRuta"])){
    // Obtener el ID de la ruta
    $idRuta = $_POST["idRuta"];

    // Crear una instancia del modelo Archivo
    $archivo = new Archivo();

    // Obtener la ruta del archivo a partir de su ID
    $rutaArchivo = $archivo->obtenerRutaArchivo($idRuta)[0]["ruta_archivo"];

    // Obtener el nombre del archivo a partir de su ruta
    $nombreArchivo = basename($rutaArchivo);

    // Convertir el nombre del archivo a formato JSON y enviarlo como respuesta
    echo json_encode($nombreArchivo, JSON_UNESCAPED_UNICODE);
    exit();
}

?>
