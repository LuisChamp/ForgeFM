<?php 
// Se requieren los archivos de las clases Carpeta y Usuario
require_once "../../../models/carpeta.php";
require_once "../../../models/usuario.php";

// Comienza la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si se ha enviado el ID de la ruta
if(isset($_POST["idRuta"])){
    // Obtiene el ID de la ruta
    $idRuta = $_POST["idRuta"];

    // Crea instancias de las clases Carpeta y Usuario
    $carpeta = new Carpeta();
    $usuario = new Usuario();

    // Obtiene la ruta completa de la carpeta
    $ruta = $carpeta->obtenerRutaCarpeta($idRuta);   
    $rutaCompleta = $ruta[0]["ruta_carpeta"];

    // Divide la ruta completa en partes
    $partesRuta = explode("/", $rutaCompleta);

    // Encuentra el índice donde comienza la sección deseada de la ruta
    $indiceInicio = array_search("carpetas", $partesRuta);

    // Obtiene el ID de la carpeta de inicio y el nombre de usuario de la sesión
    $idCarpetaInicio = $_SESSION["ruta_carpeta"];
    $nombreUsuario = $_SESSION["usuario"];

    // Obtiene el ID de usuario correspondiente al nombre de usuario de la sesión
    $idUsuario = $usuario->obtenerIdUsuario($nombreUsuario);

    // Inicializa variables para almacenar los nodos compartidos y la cadena de ruta HTML
    $nodosCompartidos = [];

    $rutaHTML = '<a href="#" data-id="'.$idCarpetaInicio.'" class="ruta_compartidos">Compartidos</a> <img src="../../assets/imgs/svg/principal/arrow.svg" alt="" class="svg-arrow">';

    $rutaActual = '';
    $nodosCompartidos = [];
    // Para almacenar los ID de las rutas compartidas
    $idRutasCompartidas = [];
    // Explora la ruta desde el índice donde comienzan las carpetas de interés
    for ($i = $indiceInicio + 1; $i < count($partesRuta); $i++) {
        // Construye la ruta incrementando con cada segmento
        $rutaActual = implode("/", array_slice($partesRuta, 0, $i + 1));  
        
        // Obtiene el ID de la ruta y el ID de la carpeta desde el mapa de rutas
        $idRutaCarpeta = $carpeta->obtenerIdMapRuta($rutaActual)[0]["id"];
        $idCarpeta = $carpeta->obtenerIdDesdeMap($idRutaCarpeta)[0]["id_carpeta"];

        // Verifica si la carpeta actual es compartida al usuario
        if ($carpeta->esCarpetaCompartida($idCarpeta, $idUsuario)) {
            // Almacena el nodo compartido y el ID de la ruta compartida
            $nodosCompartidos[] = $partesRuta[$i];
            // Almacenar los ID de rutas compartidas
            $idRutasCompartidas[] = $idRutaCarpeta;
        } else {
            // Reinicia la cadena de nodos compartidos si se encuentra un nodo no compartido
            $nodosCompartidos = [];
            $idRutasCompartidas = [];
        }
    }

    // Construye la ruta HTML basada solo en los nodos compartidos
    foreach ($nodosCompartidos as $index => $nodo) {
        $rutaHTML .= '<span class="elemento_ruta_app" data-id="' . $idRutasCompartidas[$index] . '">' . $nodo . '</span><img src="../../assets/imgs/svg/principal/arrow.svg" alt="" class="svg-arrow">';
    }

    // Imprime la ruta HTML como respuesta JSON
    $rutaApp = "RUTA: " . $rutaHTML;

    echo json_encode($rutaApp);
}

?>
