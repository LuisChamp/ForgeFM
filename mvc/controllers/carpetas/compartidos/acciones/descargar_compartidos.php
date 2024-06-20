<?php 
// Se requieren los archivos de las clases necesarias
require_once "../../../../models/carpeta.php";
require_once "../../../../models/usuario.php";
require_once "../../../../models/notificacionCarpeta.php";

// Comienza la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si se ha enviado el ID de la ruta
if(isset($_POST["idRuta"])){
    // Obtiene el ID de la ruta
    $idRuta = $_POST["idRuta"];

    // Crea instancias de las clases Carpeta, Usuario y NotificacionCarpeta
    $carpeta = new Carpeta();
    $usuario = new Usuario();
    $notificacionCarpeta = new NotificacionCarpeta();

    // Obtiene la ruta de la carpeta y su nombre
    $rutaCarpeta = $carpeta->obtenerRutaCarpeta($idRuta)[0]["ruta_carpeta"];
    $nombreCarpeta = basename($rutaCarpeta);

    // Obtiene el ID de usuario correspondiente al nombre de usuario de la sesión
    $id_usuario = $usuario->obtenerIdUsuario($_SESSION["usuario"]);

    // Obtiene las rutas compartidas de la carpeta para la descarga
    $rutas = $carpeta->obtenerRutasCompartidos($rutaCarpeta,$id_usuario);

    // Inicializa la variable para verificar si la carpeta esta vacia
    $vacio = false;

    // Verifica si las rutas compartidas están vacías
    if(empty($rutas)){
        // Marca como vacío y crea el archivo zip con la carpeta actual
        $vacio = true;
        $nombreZip = $carpeta->crearZipCompartidos($rutaCarpeta,$nombreCarpeta,$vacio);
    } else {
        // Crea el archivo zip con las rutas compartidas
        $nombreZip = $carpeta->crearZipCompartidos($rutas,$nombreCarpeta,$vacio);
    }

    // Verifica si ha ocurrido algún error al crear el archivo zip
    if(!$nombreZip){
        // Notifica un error si no se ha podido comprimir la carpeta
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorComprimirCarpeta($nombreCarpeta,$id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);        
        exit();
    } 

    // Notifica que la carpeta se ha comprimido y está lista para descargar
    $estructuraNotificacion = $notificacionCarpeta->notificarDescargarCarpeta($nombreCarpeta,$id_usuario);

    echo json_encode(['respuesta' => 'exito','notificacion' => $estructuraNotificacion, 'url' => $nombreZip], JSON_UNESCAPED_UNICODE);        
    exit();
}

?>
