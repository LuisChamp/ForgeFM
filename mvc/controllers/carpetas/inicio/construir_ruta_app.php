<?php 
// Se incluyen los archivos de clase necesarios
require_once "../../../models/carpeta.php";
require_once "../../../models/usuario.php";

// Se verifica si la sesión está activa, de lo contrario se inicia una nueva sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Se verifica si se ha recibido el parámetro 'idRuta'
if(isset($_POST["idRuta"])){
    $idRuta = $_POST["idRuta"];

    // Se instancia la clase Carpeta
    $carpeta = new Carpeta();

    // Se obtiene la ruta completa de la carpeta con el ID proporcionado
    $ruta = $carpeta->obtenerRutaCarpeta($idRuta);
        
    // Se obtiene la ruta de la carpeta
    $rutaCompleta = $ruta[0]["ruta_carpeta"];

    // Dividir la ruta en partes usando el separador "/"
    $partesRuta = explode("/", $rutaCompleta);

    // Encontrar el índice donde comienza la sección deseada de la ruta
    $indiceInicio = array_search("carpetas", $partesRuta);

    // Inicializar la variable para almacenar la ruta HTML
    $idCarpetaInicio = $_SESSION["ruta_carpeta"];
    $usuario = $_SESSION["usuario"];
    $rutaHTML = '<a href="/home.php" data-id="'.$idCarpetaInicio.'" data-usuario="'.$usuario.'" class="ruta_unidad">Unidad</a><img src="../../assets/imgs/svg/principal/arrow.svg" alt="" class="svg-arrow">';

    // Construir la ruta HTML
    for ($i = $indiceInicio + 1; $i < count($partesRuta); $i++) {
        // Construir la ruta de la carpeta actual
        $rutaCarpeta = implode("/", array_slice($partesRuta, 0, $i + 1));
        
        // Se obtiene el ID de la carpeta actual
        $idCarpeta = $carpeta->obtenerIdMapRuta($rutaCarpeta)[0]["id"];

        // Generar el elemento de la ruta para cada parte
        $rutaHTML .= '<span class="elemento_ruta_app" data-id="' . $idCarpeta . '">' . $partesRuta[$i] . '</span><img src="../../assets/imgs/svg/principal/arrow.svg" alt="" class="svg-arrow">';
    }

    // Imprimir la ruta HTML
    $rutaApp = "RUTA: " . $rutaHTML;

    echo json_encode($rutaApp);
}

?>
