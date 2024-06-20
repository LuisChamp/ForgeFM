<?php 
// Incluir el modelo necesarios para el controlador
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

    // Obtener la ruta actual de la carpeta a partir de su ID
    $rutaCarpetaActual = $carpeta->obtenerRutaCarpeta($idRuta)[0]["ruta_carpeta"];

    // Obtener el nombre de la carpeta a partir de su ruta
    $nombreCarpeta = basename($rutaCarpetaActual);

    // Definir las rutas esperadas y no esperadas para las carpetas hijas
    $rutaEsperada = $rutaCarpetaActual."/%";
    $rutaNoEsperada = $rutaCarpetaActual."/%/%";

    // Obtener el nombre de usuario de la sesión actual
    $nombre_usuario = $_SESSION["usuario"];
    
    // Obtener los datos de las carpetas
    $carpetasDatos = $carpeta->obtenerCarpetas($nombre_usuario,$rutaEsperada,$rutaNoEsperada);
    
    // Inicializar una cadena para almacenar la estructura de las carpetas
    $carpetasEstructura = "";

    // Recorrer los datos de las carpetas obtenidas y construir la estructura de las carpetas
    foreach($carpetasDatos as $carpetaDatos){
        $carpetasEstructura .= $carpeta->crearCarpetaMover($carpetaDatos["id"],$carpetaDatos["nombre_carpeta"],$carpetaDatos["nombre"]);
    }

    // Convertir la estructura de carpetas a formato JSON y enviarla como respuesta
    echo json_encode($carpetasEstructura, JSON_UNESCAPED_UNICODE);
    exit();
}

?>
