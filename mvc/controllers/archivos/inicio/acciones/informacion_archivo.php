<?php 
// Incluir los archivos necesarios para el funcionamiento del script
require_once "../../../../models/archivo.php";
require_once "../../../../models/usuario.php";
require_once "../../../../models/notificacionCarpeta.php";

// Comprobar si la sesión está activa; si no lo está, iniciarla
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Comprobar si se ha enviado el parámetro "idRuta"
if(isset($_POST["idRuta"])){
    // Obtener el valor del parámetro "idRuta"
    $idRuta = $_POST["idRuta"];

    // Crear instancias de las clases Archivo, Usuario y NotificacionCarpeta
    $archivo = new Archivo();
    $usuario = new Usuario();
    $notificacionCarpeta = new NotificacionCarpeta();

    // Obtener el ID de usuario asociado a la sesión actual
    $id_usuario = $usuario->obtenerIdUsuario($_SESSION["usuario"]);

    // Obtener la ruta del archivo correspondiente al idRuta proporcionado
    $ruta = $archivo->obtenerRutaArchivo($idRuta);

    // Extraer la ruta del archivo del resultado obtenido
    $rutaArchivo= $ruta[0]["ruta_archivo"];

    // Validar si la ruta del archivo es válida
    $validarRutaArchivo = $archivo->validarRutaArchivo($rutaArchivo);

    // Si la ruta del archivo no es válida, enviar un mensaje de error
    if(!$validarRutaArchivo){
        echo json_encode("No se ha encontrado la ruta");
    }

    // Obtener los datos del archivo asociado a la ruta
    $obtenerDatosArchivo = $archivo->obtenerDatosArchivo($rutaArchivo);

    // Convertir la fecha de creación del archivo al formato deseado (día/mes/año)
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $obtenerDatosArchivo[0]['fecha_creacion']);
    $obtenerDatosArchivo[0]['fecha_creacion'] = $date->format('d/m/Y');

    // Obtener el tamaño del archivo en un formato legible (B, KB, MB, GB)
    $obtenerDatosArchivo[0]['tamanio'] = $archivo->obtenerFormatoTamanio2($obtenerDatosArchivo[0]['tamanio']);

    // Codificar los datos del archivo en formato JSON y enviarlos como respuesta
    echo json_encode(["datosArchivo" => $obtenerDatosArchivo[0]]);
    exit();
}

?>
