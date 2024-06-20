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
    $rutaCarpeta = $ruta[0]["ruta_carpeta"];

    // Obtiene el nombre de usuario de la sesión
    $nombreUsuario = $_SESSION["usuario"];

    // Obtiene el ID del receptor correspondiente al nombre de usuario de la sesión
    $idReceptor = $usuario->obtenerIdUsuario($nombreUsuario);

    // Verifica si la ruta actual es la carpeta raíz del usuario
    if($idRuta === $_SESSION["ruta_carpeta"]){
        // Obtiene las carpetas compartidas en la carpeta raíz del usuario
        $carpetasDatos = $carpeta->obtenerCarpetasCompartidasRaiz($idReceptor);
    } else {
        // Obtiene las carpetas compartidas en la ruta específica para el receptor
        $carpetasDatos = $carpeta->obtenerCarpetasCompartidas($rutaCarpeta,$idReceptor);
    }

    // Inicializa una variable para almacenar la estructura de carpetas
    $carpetasEstructura = "";

    // Recorre las carpetas obtenidas y crea la estructura de carpetas compartidas
    foreach($carpetasDatos as $carpetaDatos){
        $carpetasEstructura .= $carpeta->crearCarpetaCompartida($carpetaDatos["id"],$carpetaDatos["nombre_carpeta"],$carpetaDatos["nombre"],$carpetaDatos["fecha_creacion"]);
    }

    // Convierte la estructura de carpetas a formato JSON y la imprime
    echo json_encode($carpetasEstructura);
}

?>
