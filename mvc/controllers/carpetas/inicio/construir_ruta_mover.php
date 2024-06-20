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
    $rutaCompleta = $ruta[0]["ruta_carpeta"];

    // Dividir la ruta en partes usando el separador "/"
    $partesRuta = explode("/", $rutaCompleta);
    
    // Encontrar el índice donde comienza la sección deseada de la ruta
    $indiceInicio = array_search("carpetas", $partesRuta);

    // Inicializar la variable para almacenar la ruta HTML
    $idCarpetaInicio = $_SESSION["ruta_carpeta"];
    $usuario = $_SESSION["usuario"];

    $active = "";

    // Verificar si la carpeta actual es la carpeta de inicio para agregar la clase 'active'
    if($idRuta === $idCarpetaInicio){
        $active = "active";
    }

    // Construir el primer elemento de la ruta HTML
    $rutaHTML = '<p>Ruta actual:</p><span class="parte_ruta_mover '.$active.'" data-id="'.$idCarpetaInicio.'">Unidad</span><i><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#000000" viewBox="0 0 256 256"><path d="M184.49,136.49l-80,80a12,12,0,0,1-17-17L159,128,87.51,56.49a12,12,0,1,1,17-17l80,80A12,12,0,0,1,184.49,136.49Z"></path></svg></i>';

    // Construir la ruta HTML
    for ($i = $indiceInicio + 1; $i < count($partesRuta); $i++) {
        // Construir la ruta de la carpeta actual
        $rutaCarpeta = implode("/", array_slice($partesRuta, 0, $i + 1));
        
        // Se obtiene el ID de la carpeta actual
        $idCarpeta = $carpeta->obtenerIdMapRuta($rutaCarpeta)[0]["id"];

        // Si es la última parte de la ruta, se marca como activa
        if($i === count($partesRuta) - 1){
            $active = "active";
        }

        // Generar el elemento de la ruta para cada parte
        $rutaHTML .= '<span class="parte_ruta_mover '.$active.'" data-id="'.$idCarpeta.'">'.$partesRuta[$i].'</span><i><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#000000" viewBox="0 0 256 256"><path d="M184.49,136.49l-80,80a12,12,0,0,1-17-17L159,128,87.51,56.49a12,12,0,1,1,17-17l80,80A12,12,0,0,1,184.49,136.49Z"></path></svg></i>';
    }

    // Se imprime la ruta HTML
    echo json_encode($rutaHTML);
}

?>
