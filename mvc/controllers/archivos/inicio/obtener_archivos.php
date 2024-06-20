<?php 
// Incluir los archivos necesarios para el funcionamiento del script
require_once "../../../models/archivo.php";
require_once "../../../models/carpeta.php";
require_once "../../../models/usuario.php";

// Iniciar sesión si no hay una sesión existente
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si se ha enviado el ID de la ruta
if(isset($_POST["idRuta"])){
    // Obtener el ID de la ruta desde el formulario
    $idRuta = $_POST["idRuta"];

    // Crear una instancia de la clase Carpeta
    $carpeta = new Carpeta();

    // Obtener la ruta de la carpeta utilizando su ID
    $ruta = $carpeta->obtenerRutaCarpeta($idRuta);

    // Extraer la ruta de la carpeta obtenida
    $rutaCarpeta = $ruta[0]["ruta_carpeta"];

    // Definir las rutas esperadas y no esperadas para la búsqueda de archivos
    $rutaEsperada = $rutaCarpeta."/%"; // Ruta esperada para archivos directamente dentro de la carpeta
    $rutaNoEsperada = $rutaCarpeta."/%/%"; // Ruta no esperada para archivos dentro de subcarpetas

    // Crear una instancia de la clase Archivo
    $archivo = new Archivo();

    // Obtener el nombre de usuario de la sesión actual
    $nombre_usuario = $_SESSION["usuario"];

    // Obtener datos de archivos dentro de la carpeta específica
    $archivosDatos = $archivo->obtenerArchivos($nombre_usuario,$rutaEsperada,$rutaNoEsperada);

    // Variable para almacenar la estructura de archivos en formato HTML
    $archivosEstructura = "";

    // Recorrer los datos de archivos obtenidos
    foreach($archivosDatos as $archivoDatos){
        // Crear la estructura HTML para cada archivo
        $archivosEstructura .= $archivo->crearArchivo($archivoDatos["id"],$archivoDatos["nombre_archivo"],$archivoDatos["nombre"],$archivoDatos["ruta_icono"],$archivoDatos["tamanio"],$archivoDatos["fecha_creacion"]);
    }

    // Convertir la estructura de archivos a formato JSON y enviarla como respuesta
    echo json_encode($archivosEstructura);
}
?>
