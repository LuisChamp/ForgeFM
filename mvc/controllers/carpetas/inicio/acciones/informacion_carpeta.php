<?php 
// Incluir archivos de las clases necesarias
require_once "../../../../models/carpeta.php";
require_once "../../../../models/usuario.php";
require_once "../../../../models/notificacionCarpeta.php";

// Iniciar sesión si aún no se ha iniciado
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si se ha enviado el ID de la ruta
if(isset($_POST["idRuta"])){
    // Obtener el ID de la ruta
    $idRuta = $_POST["idRuta"];

    // Crear instancias de las clases Carpeta, Usuario y NotificacionCarpeta
    $carpeta = new Carpeta();
    $usuario = new Usuario();
    $notificacionCarpeta = new NotificacionCarpeta();

    // Obtener el ID del usuario de la sesión actual
    $id_usuario = $usuario->obtenerIdUsuario($_SESSION["usuario"]);

    // Obtener la ruta de la carpeta a partir de su ID
    $ruta = $carpeta->obtenerRutaCarpeta($idRuta);
    $rutaCarpeta = $ruta[0]["ruta_carpeta"];

    // Validar si la ruta de la carpeta existe
    $validarRutaCarpeta = $carpeta->validarRutaCarpeta($rutaCarpeta);

    // Si la ruta de la carpeta no existe, devolver un mensaje de error
    if(!$validarRutaCarpeta){
        echo json_encode("No se ha encontrado la ruta");
    }

    // Obtener datos de la carpeta
    $obtenerDatosCarpeta = $carpeta->obtenerDatosCarpeta($rutaCarpeta);

    // Formatear la fecha de creación de la carpeta al formato dd/mm/YYYY
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $obtenerDatosCarpeta[0]['fecha_creacion']);
    $obtenerDatosCarpeta[0]['fecha_creacion'] = $date->format('d/m/Y');

    // Obtener información adicional de la carpeta utilizando un script
    $obtenerInfoCarpeta = shell_exec('./../../../../../scripts/verificarTamanio/datosInformacionCarpeta.sh '."'$rutaCarpeta'");

    // Devolver datos de la carpeta y su información adicional en formato JSON
    echo json_encode(["datosCarpeta" => $obtenerDatosCarpeta[0], "infoCarpeta" => $obtenerInfoCarpeta]);

    exit();
}

?>
