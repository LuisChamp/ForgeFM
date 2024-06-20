<?php 
// Se requieren los archivos de las clases necesarias
require_once "../../../models/archivo.php";
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

    // Crea instancias de las clases Archivo y Carpeta
    $archivo = new Archivo();
    $carpeta = new Carpeta();

    // Obtiene la ruta de la carpeta correspondiente al ID de la ruta
    $ruta = $carpeta->obtenerRutaCarpeta($idRuta);

    // Obtiene la ruta de la carpeta
    $rutaCarpeta = $ruta[0]["ruta_carpeta"];

    // Define las rutas esperadas y no esperadas para los archivos en la papelera
    $rutaEsperada = $rutaCarpeta."/%";
    $rutaNoEsperada = $rutaCarpeta."/%/%";
    
    // Obtiene el nombre de usuario de la sesión actual
    $nombre_usuario = $_SESSION["usuario"];

    // Obtiene los datos de los archivos en la papelera
    $archivosDatos = $archivo->obtenerArchivosPapelera($nombre_usuario,$rutaEsperada, $rutaNoEsperada);

    // Inicializa una variable vacia para almacenar la estructura de los archivos en formato JSON
    $archivosEstructura = "";

    // Recorre los datos de los archivos y crea la estructura para cada archivo
    foreach($archivosDatos as $archivoDatos){
        $archivosEstructura .= $archivo->crearArchivoPapelera($archivoDatos["id"],$archivoDatos["nombre_archivo"],$archivoDatos["nombre"],$archivoDatos["id_archivo_papelera"],$archivoDatos["ruta_icono"],$archivoDatos["tamanio"],$archivoDatos["fecha_creacion"]);
    }

    // Convierte la estructura de archivos a formato JSON y lo manda
    echo json_encode($archivosEstructura);
}
?>
