<?php 
// Incluir los archivos de modelo necesarios para el controlador
require_once "../../../models/carpeta.php";

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
    $ruta = $carpeta->obtenerRutaCarpeta($idRuta);

    // Extraer la ruta de la carpeta obtenida
    $rutaCarpeta = $ruta[0]["ruta_carpeta"];

    // Definir las rutas esperadas y no esperadas para las carpetas de la papelera
    $rutaEsperada = $rutaCarpeta."/%";
    $rutaNoEsperada = $rutaCarpeta."/%/%";

    // Obtener el nombre de usuario de la sesión actual
    $nombre_usuario = $_SESSION["usuario"];

    // Obtener los datos de las carpetas de la papelera basados en el nombre de usuario y las rutas esperadas
    $carpetasDatos = $carpeta->obtenerCarpetasPapelera($nombre_usuario,$rutaEsperada,$rutaNoEsperada);

    // Inicializar una cadena para almacenar la estructura de las carpetas
    $carpetasEstructura = "";

    // Recorrer los datos de las carpetas obtenidas y construir la estructura de las carpetas
    foreach($carpetasDatos as $carpetaDatos){
        $carpetasEstructura .= $carpeta->crearCarpetaPapelera($carpetaDatos["id"],$carpetaDatos["nombre_carpeta"],$carpetaDatos["nombre"],$carpetaDatos["id_carpeta_papelera"],$carpetaDatos["fecha_creacion"]);
    }

    // Convertir la estructura de carpetas a formato JSON y enviarla como respuesta
    echo json_encode($carpetasEstructura);
}

?>
