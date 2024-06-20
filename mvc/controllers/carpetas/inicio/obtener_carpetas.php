<?php 
// Se incluyen los archivos de las clases necesarias
require_once "../../../models/carpeta.php";
require_once "../../../models/usuario.php";

// Se inicia una sesión si no hay una sesión activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Se verifica si se ha enviado el ID de la ruta de la carpeta
if(isset($_POST["idRuta"])){
    // Se obtiene el ID de la ruta de la carpeta
    $idRuta = $_POST["idRuta"];

    // Se crea una instancia de la clase Carpeta
    $carpeta = new Carpeta();

    // Se obtiene la ruta completa de la carpeta correspondiente al ID enviado
    $ruta = $carpeta->obtenerRutaCarpeta($idRuta);
    $rutaCarpeta = $ruta[0]["ruta_carpeta"];

    // Se define la ruta esperada y la ruta no esperada para la obtención de carpetas
    $rutaEsperada = $rutaCarpeta."/%";
    $rutaNoEsperada = $rutaCarpeta."/%/%";

    // Se obtiene el nombre de usuario de la sesión actual
    $nombre_usuario = $_SESSION["usuario"];

    // Se obtienen los datos de las carpetas que corresponden a la ruta esperada y no esperada
    $carpetasDatos = $carpeta->obtenerCarpetas($nombre_usuario, $rutaEsperada, $rutaNoEsperada);
    
    // Se inicializa una variable para almacenar la estructura de las carpetas
    $carpetasEstructura = "";

    // Se recorren los datos de las carpetas obtenidas
    foreach($carpetasDatos as $carpetaDatos){
        // Se crea la estructura HTML de cada carpeta
        $carpetasEstructura .= $carpeta->crearCarpeta($carpetaDatos["id"], $carpetaDatos["nombre_carpeta"], $carpetaDatos["nombre"], $carpetaDatos["fecha_creacion"]);
    }

    // Se convierte la estructura de las carpetas a formato JSON y se imprime
    echo json_encode($carpetasEstructura);
}

?>
