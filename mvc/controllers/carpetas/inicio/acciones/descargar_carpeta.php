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

    // Obtener la ruta de la carpeta a partir de su ID y su nombre
    $rutaCarpeta = $carpeta->obtenerRutaCarpeta($idRuta)[0]["ruta_carpeta"];
    $nombreCarpeta = basename($rutaCarpeta);

    // Obtener el ID del usuario actualmente autenticado
    $id_usuario = $usuario->obtenerIdUsuario($_SESSION["usuario"]);

    // Crear un archivo ZIP que contenga la carpeta y sus contenidos
    $nombreZip = $carpeta->crearZipCarpeta($rutaCarpeta,$nombreCarpeta);
   
    // Verificar si se pudo crear el archivo ZIP
    if(!$nombreZip){
        // Notificar un error si no se pudo crear el archivo ZIP
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorComprimirCarpeta($nombreCarpeta,$id_usuario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);        
        exit();
    } 

    // Notificar éxito al descargar la carpeta y proporcionar la URL del archivo ZIP
    $estructuraNotificacion = $notificacionCarpeta->notificarDescargarCarpeta($nombreCarpeta,$id_usuario);
    echo json_encode(['respuesta' => 'exito','notificacion' => $estructuraNotificacion, 'url' => $nombreZip], JSON_UNESCAPED_UNICODE);        
    exit();

}

?>
