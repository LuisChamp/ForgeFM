<?php 
// Incluir los archivos necesarios para el funcionamiento del script
require_once "../../../../models/archivo.php";
require_once "../../../../models/usuario.php";

// Comprobar si la sesión está activa; si no lo está, iniciarla
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Comprobar si se proporcionó el parámetro "idRuta"
if(isset($_GET["idRuta"])){

    // Obtener el valor del parámetro "idRuta" desde la URL
    $idRuta = $_GET["idRuta"];

    // Crear instancias de las clases Archivo y Usuario
    $archivo = new Archivo();
    $usuario = new Usuario();

    // Obtener la ruta del archivo correspondiente al idRuta proporcionado
    $rutaArchivo = $archivo->obtenerRutaArchivo($idRuta)[0]["ruta_archivo"];

    // Obtener el nombre base del archivo a partir de su ruta
    $nombreArchivo = basename($rutaArchivo);
    
    // Configurar las cabeceras HTTP para indicar que se trata de una descarga de archivo
    header('Content-Length: ' . filesize($rutaArchivo)); // Tamaño del archivo
    header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"'); // Nombre del archivo
    header('Content-Transfer-Encoding: binary'); // Codificación binaria
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0'); // Control de caché
    header('Pragma: public'); // Indicador de que la respuesta es pública

    // Limpiar todos los niveles de salida del búfer
    ob_clean();
    flush();

    // Leer y enviar el archivo al cliente
    readfile($rutaArchivo);
    exit;
}

?>
